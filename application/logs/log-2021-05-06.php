<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-05-06 08:15:43 --> Query error: Table 'app.ac_payroll_item' doesn't exist - Invalid query: SELECT ac_payroll_item.id, 
						barcode, ac_payroll_item.name AS employee_name, 
						is_pt,
						ansena_department.name AS 'department',
						ansena_division.division, 
						ansena_team.team,
						ac_payroll_item.shift,
						DATE_FORMAT(time_scan, '%H:%i:%s') AS time_scan, 
						events_attend.attend
						FROM ac_payroll_item
						JOIN ansena_department ON ansena_department.id = ac_payroll_item.office
						JOIN ansena_division ON ansena_division.id = ac_payroll_item.division
						JOIN events_attend ON events_attend.ac_payroll_item_id = ac_payroll_item.id
						LEFT JOIN ansena_team_detail ON ansena_team_detail.ac_payroll_item_id = ac_payroll_item.id
						LEFT JOIN ansena_team ON ansena_team.id = ansena_team_detail.ansena_team_id
						WHERE events_attend.event_id = 6
						ORDER BY time_scan DESC
