<?php
/**
 * Company Department
 *
 * @package HostCMS
 * @subpackage Company
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2018 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
return array(
	'model_name' => 'Структура',
	'title' => 'Структура компании "%s"',
	'company' => '<acronym title="Компания">Компания</acronym>',
	'warning' => 'С сайтом не связано ни одной компании',

	'add_form_title' => 'Добавление отдела',
	'edit_form_title' => 'Редактирование отдела',
	'delete_form_title' => 'Удаление отдела',
	'add_user_title' => 'Добавление сотрудника в отдел',
	'edit_user_title' => 'Редактирование информации о должности сотрудника',
	'delete_user_title' => 'Удаление сотрудника из отдела',


	'name' => '<acronym title="Название отдела">Название</acronym>',
	'parent_id' => '<acronym title="Вышестоящий отдел">Вышестоящий отдел</acronym>',
	'address' => '<acronym title="Адрес отдела">Адрес</acronym>',
	'description' => '<acronym title="Подробная информация об отделе">Описание</acronym>',

	'caption' => '<acronym title="Название отдела">Отдел</acronym>',

	'caption_block_users' => 'Сотрудники',

	'add' => 'Добавить',
	'edit' => 'Изменить',
	'cancel' => 'Отменить',

	'addUserTitleAction' => 'Добавить сотрудника',
	'editTitleAction' => 'Редактировать отдел',
	'deleteTitleAction' => 'Удалить отдел',
	'editUserDepartmentPostTitleAction' => 'Редактировать',
	'deleteUserDepartmentPostTitleAction' => 'Удалить из отдела',
	'moduleTitleAction' => 'Доступ к модулям',
	'actionTitleAction' => 'Доступ к действиям',

	'moveMessage' => 'Вы действительно хотите переместить отдел?',
	'deleteMessage' => 'Вы действительно хотите удалить отдел и его дочерние отделы?',
	'deleteUserMessage' => 'Вы действительно хотите удалить сотрудника из отдела?',

	'addUserToHeads_success' => '%s назначен руководителем %s',
	'addUserToHeads_error' => '%s уже находится на должности %s в %s',
	'deleteUserFromHeads_success' => '%s снят с руководящей должности %s',

	'changeUserDepartmentPost_success' => '%s переведен на должность %s в %s',
	'addUserToDepartmentPost_success' => '%s назначен на должность %s в %s',

	'add_success' => 'Отдел добавлен.',
	'apply_success' => 'Информация об отделе изменена.',
	'markDeleted_success' => 'Отдел удален.',
	'markDeleted_error' => 'Отдел не удален!',
	'delete_success' => 'Отдел удален.',

	'addUser_success' => 'Сотрудник добавлен в отдел.',
	'applyUser_success' => 'Информация о должности сотрудника в отделе изменена.',
	'addUser_error' => 'Сотрудник уже работает на этой должности в этом отделе.',

	'deleteUserFromDepartment_success' => "Сотрудник %s удален с должности %s в %s",

	'departmentExistence_error' => 'Ошибка! Такого отдела не существует.',
);