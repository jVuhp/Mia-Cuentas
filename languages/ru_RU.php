<?php
$messages = [
	'display' => ['ru', 'by', 'kz', 'kg', 'tj', 'tm', 'uz'],
	'location' => [
		'flag' => 'ru',
		'name' => 'Испанский',
	],
	'dropdown' => [
		'logged_in' => 'Вошли в систему как',
		'toggle_theme' => 'Изменить тему',
		'select_lang' => 'Выберите язык',
		'logout' => 'Выйти',
	],
	'theme' => [
		'light' => 'Светлая',
		'dark' => 'Темная',
		'mode' => [
			'dark' => 'Темный режим',
			'light' => 'Светлый режим',
		],
	],
	
	
	'title' => [
		'name' => 'Моя учетная запись - Официальный сайт',
	],
	
	'navbar' => [
		'wallet' => [
			'icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-wallet"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>',
			'text' => 'Бумажник',
			'sub_nav' => [
				'create' => [
					'icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>',
					'text' => 'Создать новый кошелек',
				],
			],
		],
		'cuentas' => [
			'icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>',
			'text' => 'Счета',
			'sub_nav' => [
				'kick' => [
					'icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user-cancel"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" /><path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M17 21l4 -4" /></svg>',
					'text' => 'Удалить участника',
				],
				'invite' => [
					'icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-mail-forward"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 18h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v7.5" /><path d="M3 6l9 6l9 -6" /><path d="M15 18h6" /><path d="M18 15l3 3l-3 3" /></svg>',
					'text' => 'Пригласить участника',
				],
				'create' => [
					'icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M16 19h6" /><path d="M19 16v6" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /></svg>',
					'text' => 'Создать новую учетную запись',
				],
			],
		],
		'admin' => [
			'icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-tabler"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 9l3 3l-3 3" /><path d="M13 15l3 0" /><path d="M4 4m0 4a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" /></svg>',
			'text' => 'Администратор',
			'sub_nav' => [
				'index' => [
					'icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-dashboard"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M13.45 11.55l2.05 -2.05" /><path d="M6.4 20a9 9 0 1 1 11.2 0z" /></svg>',
					'text' => 'Главная',
				],
				'request' => [
					'icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye-question"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M14.071 17.764a8.989 8.989 0 0 1 -2.071 .236c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.346 0 6.173 1.727 8.482 5.182" /><path d="M19 22v.01" /><path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" /></svg>',
					'text' => 'Запросы',
				],
				'wallet' => [
					'icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-wallet"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>',
					'text' => 'Бумажники',
				],
				'account' => [
					'icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>',
					'text' => 'Счета',
				],
			],
		],
	],
	
	'login' => [
		'title' => 'Войдите в свою учетную запись',
		'subtitle' => 'Войдите в свою учетную запись Google, чтобы продолжить на платформе.',
		'button' => 'Войти с помощью Google',
	],
	
	'admin' => [
		'index' => [
			'title' => 'Начало',
			'subtitle' => 'Административная секция Mia Accounts!',
			'table' => [
				'name' => 'Имя',
				'users' => 'Админы',
				'account' => 'Счета',
				'balance' => 'Баланс',
				'receipt' => 'Квитанции',
				'status' => [
					'suspend' => 'Приостановлен',
					'verify' => 'В Процессе',
					'active' => 'Активен',
				],
				'registered' => 'Зарегистрирован',
				'actions' => '',
				'buttons' => [
					'manage' => 'Управление',
					'edit' => 'Изменить',
					'suspend' => 'Приостановить',
					'unsuspend' => 'Возобновить',
					'verify' => 'Проверить',
					'delete' => 'Удалить',
				],
			],
		],
		'request' => [
			'title' => 'Запросы',
			'subtitle' => 'Принимайте кошельки и счета, которые вы хотите посетить в программном обеспечении.',
			'buttons' => [
				'wallet' => [
					'title' => 'Запросы на',
					'subtitle' => 'Кошельки',
				],
				'account' => [
					'title' => 'Запросы на',
					'subtitle' => 'Счета',
				],
			],
			'table' => [
				'wallet' => [
					'name' => 'Имя',
					'owner' => 'Владелец',
					'status' => [
						'title' => 'Статус',
						'suspend' => 'Приостановлен',
						'verify' => 'Не Проверен',
						'active' => 'Активен',
					],
					'registered' => 'Зарегистрирован',
					'actions' => '',
					'buttons' => [
						'verify' => 'Проверить',
						'delete' => 'Удалить',
					],
				],
				'account' => [
					'user' => 'Пользователь',
					'gid' => 'Идентификатор Google',
					'status' => [
						'title' => 'Статус',
						'suspend' => 'Приостановлен',
						'verify' => 'Не Проверен',
						'active' => 'Активен',
					],
					'registered' => 'Зарегистрирован',
					'actions' => '',
					'buttons' => [
						'verify' => 'Проверить',
						'delete' => 'Удалить',
					],
				],
			],
			'modal' => [
				'wallet' => [
					'verify' => [
						'title' => 'Проверить кошелек',
						'name' => [
							'label' => 'Имя кошелька',
							'placeholder' => 'Неизвестно',
						],
						'balance' => [
							'label' => 'Максимальный баланс:',
							'placeholder' => '300000',
						],
						'account' => [
							'label' => 'Максимальные счета:',
							'placeholder' => '20',
						],
						'receipt' => [
							'label' => 'Максимальные квитанции:',
							'placeholder' => '100',
						],
						'description' => '<b class="text-warning">ПРИМЕЧАНИЕ:</b> Любое из трех полей, если установлено на "0", будет неограниченным.',
						'buttons' => [
							'cancel' => 'Отмена',
							'submit' => 'Проверить',
						],
					],
					'edit' => [
						'title' => 'Редактировать кошелек',
						'name' => [
							'label' => 'Имя кошелька',
							'placeholder' => 'Неизвестно',
						],
						'balance' => [
							'label' => 'Максимальный баланс:',
							'placeholder' => '300000',
						],
						'account' => [
							'label' => 'Максимальные счета:',
							'placeholder' => '20',
						],
						'receipt' => [
							'label' => 'Максимальные квитанции:',
							'placeholder' => '100',
						],
						'description' => '<b class="text-warning">ПРИМЕЧАНИЕ:</b> Любое из трех полей, если установлено на "0", будет неограниченным.',
						'buttons' => [
							'cancel' => 'Отмена',
							'submit' => 'Сохранить',
						],
					],
				],
				'user' => [
					'verify' => [
						'title' => 'Проверить пользователя',
						'name' => [
							'label' => 'Имя пользователя',
							'placeholder' => 'Неизвестно',
						],
						'wallet' => [
							'label' => 'Максимальные кошельки:',
							'placeholder' => '5',
						],
						'description' => '<b class="text-warning">ПРИМЕЧАНИЕ:</b> Если установлено на "0", это неограничено.',
						'buttons' => [
							'cancel' => 'Отмена',
							'submit' => 'Проверить',
						],
					],
					'edit' => [
						'title' => 'Редактировать пользователя',
						'name' => [
							'label' => 'Имя пользователя',
							'placeholder' => 'Неизвестно',
						],
						'wallet' => [
							'label' => 'Максимальные кошельки:',
							'placeholder' => '5',
						],
						'description' => '<b class="text-warning">ПРИМЕЧАНИЕ:</b> Если установлено на "0", это неограничено.',
						'buttons' => [
							'cancel' => 'Отмена',
							'submit' => 'Сохранить',
						],
					],
				],
			],
		],
		'wallet' => [
			'title' => 'Кошельки',
			'subtitle' => 'Управляйте существующими кошельками в качестве администратора.',
			'table' => [
				'name' => 'Имя',
				'users' => 'Админы',
				'account' => 'Счета',
				'balance' => 'Баланс',
				'receipt' => 'Квитанции',
				'status' => [
					'title' => 'Статус',
					'suspend' => 'Приостановлен',
					'verify' => 'В Процессе',
					'active' => 'Активен',
				],
				'registered' => 'Зарегистрирован',
				'actions' => '',
				'buttons' => [
					'manage' => 'Управление',
					'edit' => 'Изменить',
					'suspend' => 'Приостановить',
					'unsuspend' => 'Возобновить',
					'delete' => 'Удалить',
				],
			],
		],
		'account' => [
			'title' => 'Счета',
			'subtitle' => 'Управляйте существующими пользователями сайта различными способами',
			'table' => [
				'user' => 'Пользователь',
				'gmail' => 'Почта',
				'id' => 'Идентификатор Google',
				'status' => [
					'title' => 'Статус',
					'suspend' => 'Приостановлен',
					'verify' => 'В Процессе',
					'active' => 'Активен',
				],
				'registered' => 'Зарегистрирован',
				'actions' => '',
				'buttons' => [
					'manage' => 'Управление',
					'edit' => 'Изменить',
					'is_admin' => 'Сделать Админом',
					'remove_admin' => 'Удалить Админа',
					'suspend' => 'Приостановить',
					'unsuspend' => 'Возобновить',
					'delete' => 'Удалить',
				],
			],
		],
	],
	
	'cuentas' => [
		'title' => 'Счета',
		'subtitle' => 'Просмотреть все зарегистрированные счета.',
		'card' => [
			'account' => 'Счета',
			'receipts' => 'Квитанции',
			'total_balances' => 'Всего балансов',
			'paid_balance' => 'Оплаченный баланс',
			'unpaid_balance' => 'Неоплаченный баланс',
		],
		'table' => [
			'head' => [
				'account' => 'Счет',
				'debt_balance' => 'Баланс задолженности',
				'paid_balance' => 'Оплаченный баланс',
				'total_balance' => 'Общий баланс',
				'registered' => 'Зарегистрирован',
				'actions' => '',
			],
			'actions' => 'Подробнее',
		],
		'modal' => [
			'title' => 'Добавление нового счета',
			'box' => [
				'label' => 'Имя',
				'placeholder' => 'Имя человека.',
			],
			'buttons' => [
				'cancel' => 'Отмена',
				'submit' => 'Завершить',
			],
		],
		'modal2' => [
			'title' => 'Приглашение скопировано!',
			'subtitle' => 'Вы скопировали приглашение. Просто отправьте его своему партнеру, и он сможет присоединиться. Помните! Они должны быть проверенным участником.',
			'button' => 'Закрыть',
		],
		'articles' => [
			'title' => 'Счет',
			'subtitle' => 'Просмотр всех приобретенных статей по счету.',
			'buttons' => [
				'receipt' => 'Квитанции',
				'article' => 'Статья',
			],
			'card' => [
				'paid' => [
					'title' => 'Оплачено',
					'button' => 'Просмотреть квитанции',
				],
				'unpaid' => [
					'title' => 'Не оплачено',
					'button' => 'Просмотреть статьи',
				],
			],
			'table' => [
				'head' => [
					'article' => 'Статья',
					'balance' => 'Баланс',
					'registered' => 'Зарегистрирован',
					'actions' => '',
				],
				'actions' => 'Подробнее',
			],
			'modal' => [
				'new_article' => [
					'title' => 'Добавление статьи',
					'article' => [
						'label' => 'Статья',
						'placeholder' => '(Например: Половина мембраны для пастыфлоры)',
					],
					'cost' => [
						'label' => 'Стоимость',
						'placeholder' => '(Например: 8890)',
					],
					'buttons' => [
						'cancel' => 'Отмена',
						'submit' => 'Завершить',
					],
				],
				'new_receipt' => [
					'title' => 'Создание квитанции об оплате',
					'cost' => [
						'label' => 'Стоимость',
						'placeholder' => '(Например: 8890)',
						'description' => 'Квитанция используется для вычета суммы задолженности для <b>%account_name%</b>. Например, то, что должно быть оплачено, составляет <b>$%unpaid:balance%</b>.',
					],
					'description' => [
						'label' => 'Дополнительное описание',
						'placeholder' => 'Например, привет :D',
						'description' => 'Любая информация, которую вы хотите оставить для информирования о данной квитанции об оплате. Это необязательно.',
					],
					'buttons' => [
						'cancel' => 'Отмена',
						'submit' => 'Завершить',
					],
				],
				'list_receipt' => [
					'' => '',
				],
			],
		],
		'receipt' => [
			'table' => [
				'head' => [
					'description' => 'Описание',
					'total' => 'Итого',
					'registered' => 'Зарегистрирован',
					'actions' => '',
				],
				'actions' => 'Подробнее',
			],
		],
	],
	
	
	'alert' => [
		'inauth' => 'Вы не являетесь участником.',
		'user' => [
			'delete' => [
				'success' => 'Пользователь удален!',
				'unknown' => 'Неизвестный пользователь.',
			],
			'verify' => [
				'success' => 'Пользователь проверен!',
				'unknown' => 'Неизвестный пользователь.',
			],
			'edit' => [
				'success' => 'Пользователь изменен!',
				'unknown' => 'Неизвестный пользователь.',
			],
			'add_admin' => [
				'success' => 'Вы зарегистрировали пользователя как администратора!',
				'unknown' => 'Неизвестный пользователь.',
			],
			'remove_admin' => [
				'success' => 'Вы убрали пользователя как администратора!',
				'unknown' => 'Неизвестный пользователь.',
			],
			'suspend' => [
				'success' => 'Вы приостановили пользователя!',
				'unknown' => 'Неизвестный пользователь.',
			],
			'unsuspend' => [
				'success' => 'Вы сняли приостановку с пользователя!',
				'unknown' => 'Неизвестный пользователь.',
			],
		],
		'wallet' => [
			'create' => [
				'empty' => 'Не заполнены обязательные поля.',
				'success' => 'Кошелек успешно создан!',
				'limit' => 'Превышен лимит кошельков на счет.',
			],
			'login' => [
				'status' => 'Ожидание обработки кошелька.',
				'success' => 'Сеанс входа в кошелек начат!',
				'unknown' => 'Неизвестный кошелек.',
			],
			'delete' => [
				'success' => 'Кошелек удален!',
				'unknown' => 'Неизвестный кошелек.',
			],
			'verify' => [
				'success' => 'Кошелек проверен!',
				'unknown' => 'Неизвестный кошелек.',
			],
			'edit' => [
				'success' => 'Кошелек изменен!',
				'unknown' => 'Неизвестный кошелек.',
			],
			'suspend' => [
				'success' => 'Вы приостановили учетную запись пользователя!',
				'unknown' => 'Неизвестный пользователь.',
			],
			'unsuspend' => [
				'success' => 'Вы возобновили учетную запись пользователя!',
				'unknown' => 'Неизвестный пользователь.',
			],
		],
		'auth' => [
			'register' => [
				'empty' => 'Есть незаполненные поля.',
				'name_used' => 'Имя счета уже используется.',
				'success' => 'Счет успешно добавлен!',
				'unknown' => 'Неизвестный кошелек!',
			],
		],
		'articles' => [
			'create' => [
				'empty' => 'Есть незаполненные поля.',
				'success' => 'Статья успешно добавлена!',
				'unknown' => 'Неизвестный кошелек!',
			],
		],
		'receipt' => [
			'create' => [
				'empty' => 'Есть незаполненные поля.',
				'success' => 'Квитанция успешно создана!',
				'unknown' => 'Неизвестный кошелек!',
			],
		],
		'limited' => 'Вы достигли разрешенного лимита в кошельке.',
	],
	
	
	'error' => [
		'not_results_found' => 'Результаты не найдены.',
	],
	
    'filters' => [
        'search' => 'Поиск..',
        'showing' => 'Показаны :compag_to: до :end: из :results: записей',
        'unknown' => 'Неизвестный',
    ],
    'counttime' => [
		'ago-type' => 1, // 1: Назад 20 дней / 2: 20 дней назад
		'ago' => 'назад',
        'years' => 'Годы', // years or Years (example: Since 12 Years ago / Since 12 years ago)
        'year' => 'Год', // year or Year (example: Since 1 Year ago / Since 1 year ago)
        'months' => 'Месяцы',
        'month' => 'Месяц',
        'days' => 'Дни',
        'day' => 'День',
        'hours' => 'Часы',
        'hour' => 'Час',
        'minutes' => 'Минуты',
        'minute' => 'Минута',
        'seconds' => 'Секунды',
        'second' => 'Секунда',
        'separator' => 'и', // 27d, 15mins "and" 30 secs
    ],
	'months' => [
		'low' => [
			'jan' => 'Янв',
			'feb' => 'Фев',
			'mar' => 'Мар',
			'apr' => 'Апр',
			'may' => 'Май',
			'jul' => 'Июл',
			'jun' => 'Июн',
			'aug' => 'Авг',
			'sep' => 'Сен',
			'oct' => 'Окт',
			'nov' => 'Ноя',
			'dec' => 'Дек',
		],
		'complete' => [
			'jan' => 'Январь',
			'feb' => 'Февраль',
			'mar' => 'Март',
			'apr' => 'Апрель',
			'may' => 'Май',
			'jun' => 'Июнь',
			'jul' => 'Июль',
			'aug' => 'Август',
			'sep' => 'Сентябрь',
			'oct' => 'Октябрь',
			'nov' => 'Ноябрь',
			'dec' => 'Декабрь',
		],
	],
];


?>