<?php
$config =
[
		'login' =>
		[
				[
						'field' => 'username',
						'label' => 'Username',
						'rules' => 'trim|required|min_length[2]|max_length[50]',
						'errors' =>
						[
								'required' => '账号必填',
								'min_length' => '账号最少2位',
								'max_length' => '账号最多50位'
						]
				],
				[
						'field' => 'password',
						'label' => 'Password',
						'rules' => 'trim|required|min_length[6]',
						'errors' =>
						[
								'required' => '密码必填',
								'min_length' => '密码最少6位'
						]
				]
		],
		'create' =>
		[
				[
						'field' => 'username',
						'label' => 'Username',
						'rules' => 'trim|required|min_length[2]|max_length[50]',
						'errors' =>
						[
								'required' => '账号必填',
								'min_length' => '账号最少2位',
								'max_length' => '账号最多50位'
						]
				],
				[
						'field' => 'password',
						'label' => 'Password',
						'rules' => 'trim|required|min_length[6]',
						'errors' =>
						[
								'required' => '密码必填',
								'min_length' => '密码最少6位'
						]
				],
				[
						'field' => 'passconf',
						'label' => 'Password Confirmation',
						'rules' => 'trim|required|matches[password]',
						'errors' =>
						[
								'required' => '确认密码必填',
								'matches' => '两次密码不一致'
						]
				]
		],
		'password' =>
		[
				[
						'field' => 'password',
						'label' => 'Password',
						'rules' => 'trim|required|min_length[6]',
						'errors' =>
						[
								'required' => '密码必填',
								'min_length' => '密码最少6位'
						]
				],
				[
						'field' => 'passconf',
						'label' => 'Password Confirmation',
						'rules' => 'trim|required|matches[password]',
						'errors' =>
						[
								'required' => '确认密码必填',
								'matches' => '两次密码不一致'
						]
				]
		]
		
];