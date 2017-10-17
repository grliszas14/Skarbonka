# Skarbonka

After pulling this software from git 
you need to do few things to make it work:

1. Edit data needed to make connection with database. (connection.php)
2. Edit sql queries to have proper tables in them. (add.php, edit.php, index.php - 4 queries)

TODO remove 2nd point of this instruction ;)

Required database format:

mysql> SHOW COLUMNS FROM wplaty2;
+-------+------------------+------+-----+---------+----------------+
| Field | Type             | Null | Key | Default | Extra          |
+-------+------------------+------+-----+---------+----------------+
| id    | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
| data  | date             | YES  |     | NULL    |                |
| na_co | varchar(150)     | YES  |     | NULL    |                |
| kto   | varchar(30)      | YES  |     | NULL    |                |
| ile   | float(10,2)      | YES  |     | NULL    |                |
+-------+------------------+------+-----+---------+----------------+

