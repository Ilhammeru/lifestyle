<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-05-04 09:19:50 --> Query error: Table 'app.ansena_team' doesn't exist - Invalid query: SELECT CONCAT(IF(is_pt = 1, 
																		CONCAT('PT. ', REPLACE(name, 'PT. ', '')), 
																		REPLACE(name, 'PT. ', '')),
																		' (', team, ')') AS 'name',
															     CONCAT('t', ansena_team.id) AS 'id'
																 FROM ansena_team 
																 LEFT JOIN ansena_department ON ansena_department.id = ansena_team.dept_id
																 UNION ALL 
																 SELECT IF(is_pt = 1, 
																		CONCAT('PT. ', REPLACE(name, 'PT. ', '')), 
																		REPLACE(name, 'PT. ', '')) AS 'name',
																 CONCAT('d', ansena_department.id) AS 'id'
																 FROM ansena_department 
																 LEFT OUTER JOIN ansena_team ON ansena_team.dept_id = ansena_department.id 
																 WHERE ansena_team.id IS NULL
																 AND progress != 0
ERROR - 2021-05-04 09:19:52 --> Query error: Table 'app.ansena_team' doesn't exist - Invalid query: SELECT CONCAT(IF(is_pt = 1, 
																		CONCAT('PT. ', REPLACE(name, 'PT. ', '')), 
																		REPLACE(name, 'PT. ', '')),
																		' (', team, ')') AS 'name',
															     CONCAT('t', ansena_team.id) AS 'id'
																 FROM ansena_team 
																 LEFT JOIN ansena_department ON ansena_department.id = ansena_team.dept_id
																 UNION ALL 
																 SELECT IF(is_pt = 1, 
																		CONCAT('PT. ', REPLACE(name, 'PT. ', '')), 
																		REPLACE(name, 'PT. ', '')) AS 'name',
																 CONCAT('d', ansena_department.id) AS 'id'
																 FROM ansena_department 
																 LEFT OUTER JOIN ansena_team ON ansena_team.dept_id = ansena_department.id 
																 WHERE ansena_team.id IS NULL
																 AND progress != 0
ERROR - 2021-05-04 09:19:52 --> Query error: Table 'app.ansena_team' doesn't exist - Invalid query: SELECT CONCAT(IF(is_pt = 1, 
																		CONCAT('PT. ', REPLACE(name, 'PT. ', '')), 
																		REPLACE(name, 'PT. ', '')),
																		' (', team, ')') AS 'name',
															     CONCAT('t', ansena_team.id) AS 'id'
																 FROM ansena_team 
																 LEFT JOIN ansena_department ON ansena_department.id = ansena_team.dept_id
																 UNION ALL 
																 SELECT IF(is_pt = 1, 
																		CONCAT('PT. ', REPLACE(name, 'PT. ', '')), 
																		REPLACE(name, 'PT. ', '')) AS 'name',
																 CONCAT('d', ansena_department.id) AS 'id'
																 FROM ansena_department 
																 LEFT OUTER JOIN ansena_team ON ansena_team.dept_id = ansena_department.id 
																 WHERE ansena_team.id IS NULL
																 AND progress != 0
ERROR - 2021-05-04 15:03:33 --> Query error: Table 'app.ansena_team' doesn't exist - Invalid query: SELECT CONCAT(IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT. ', '')), REPLACE(name, 'PT. ', '')), ' (', team, ')') AS 'name',
															     CONCAT('t', ansena_team.id) AS 'id'
																 FROM ansena_team 
																 LEFT JOIN ansena_department ON ansena_department.id = ansena_team.dept_id 
																 UNION ALL 
																 SELECT IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT. ', '')), REPLACE(name, 'PT. ', '')) AS 'name',
																 CONCAT('d', ansena_department.id) AS 'id'
																 FROM ansena_department 
																 LEFT OUTER JOIN ansena_team ON ansena_team.dept_id = ansena_department.id 
																 WHERE ansena_team.id IS NULL
ERROR - 2021-05-04 16:05:10 --> Query error: Table 'app.ansena_team' doesn't exist - Invalid query: SELECT CONCAT(IF(is_pt = 1, 
																		CONCAT('PT. ', REPLACE(name, 'PT. ', '')), 
																		REPLACE(name, 'PT. ', '')),
																		' (', team, ')') AS 'name',
															     CONCAT('t', ansena_team.id) AS 'id'
																 FROM ansena_team 
																 LEFT JOIN ansena_department ON ansena_department.id = ansena_team.dept_id
																 UNION ALL 
																 SELECT IF(is_pt = 1, 
																		CONCAT('PT. ', REPLACE(name, 'PT. ', '')), 
																		REPLACE(name, 'PT. ', '')) AS 'name',
																 CONCAT('d', ansena_department.id) AS 'id'
																 FROM ansena_department 
																 LEFT OUTER JOIN ansena_team ON ansena_team.dept_id = ansena_department.id 
																 WHERE ansena_team.id IS NULL
																 AND progress != 0
ERROR - 2021-05-04 11:05:23 --> 404 Page Not Found: Assets/vendor
ERROR - 2021-05-04 11:05:23 --> 404 Page Not Found: Assets/vendor
