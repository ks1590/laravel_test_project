# create databases
CREATE DATABASE IF NOT EXISTS `bubo_sales_tracker`;
CREATE DATABASE IF NOT EXISTS `bubo_sales_tracker_testing`;

# create root user and grant rights
CREATE USER 'preapp_dev'@'localhost' IDENTIFIED BY 'Predevapp_1';
GRANT ALL ON *.* TO 'preapp_dev'@'%';