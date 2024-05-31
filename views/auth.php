<?php
session_start();

if (isset($codeAuth[1])) {
    parse_str($codeAuth[1], $queryParams);

    $code = $queryParams['code'];

    $redirect_uri = URI . '/auth'; 
    $token_url = 'https://accounts.google.com/o/oauth2/token';

    $token_params = array(
        'code' => $code,
        'client_id' => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code',
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $token_data = json_decode($response, true);
    $auth_verify = 0;
    if (isset($token_data['access_token'])) {
        $info_url = 'https://www.googleapis.com/oauth2/v1/userinfo';
        $info_params = array(
            'access_token' => $token_data['access_token'],
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $info_url . '?' . http_build_query($info_params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $user_info = curl_exec($ch);
        curl_close($ch);

        $user_data = json_decode($user_info, true);
		
		$verifyUserSQL = $connx->prepare("SELECT * FROM `mcs_user` WHERE `g_id` = ?;");
		$verifyUserSQL->execute([$user_data['id']]);
		if ($verifyUserSQL->RowCount() > 0) {
			$user = $verifyUserSQL->fetch(PDO::FETCH_ASSOC);
			
			if ($user['name'] != $user_data['name'] OR $user['avatar'] != $user_data['picture']) {
				$updateSQL = $connx->prepare("UPDATE `mcs_user` SET `name` = ?, `avatar` = ? WHERE `id` = ?;");
				$updateSQL->execute([$user_data['name'], $user_data['picture'], $user['id']]);
			}
			
			$_SESSION['mcs_user'] = [
				'id' => $user['id'],
				'g_id' => $user['g_id'],
				'name' => $user['name'],
				'email' => $user['email'],
				'status' => $user['status'],
				'avatar' => $user['avatar'],
				'logged' => true
			];
			
			echo '<script> location.href = "' . URI . '/cuentas"; </script>';
			
		} else {
			$status = (AUTO_VERIFY) ? 1 : 0;
			$insertSQL = $connx->prepare("INSERT INTO `mcs_user`(`g_id`, `name`, `email`, `status`, `avatar`) VALUES (?, ?, ?, ?, ?);");
			$insertSQL->execute([$user_data['id'], $user_data['name'], $user_data['email'], $status, $user_data['picture']]);
			
			$_SESSION['mcs_user'] = [
				'id' => $connx->lastInsertId(),
				'g_id' => $user_data['id'],
				'name' => $user_data['name'],
				'email' => $user_data['email'],
				'status' => $status,
				'avatar' => $user_data['picture'],
				'logged' => true
			];
			
			echo '<script> location.href = "' . URI . '/pending"; </script>';
			
		}
		
		
    } else {
		echo '<script> location.href = "' . URI . '"; </script>';
    }
}
?>