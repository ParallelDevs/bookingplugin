/**
 * Author:  amora
 * Created: Oct 10, 2017
 */

-- DB Schema for plugin
CREATE TABLE IF NOT EXISTS `ohrm_bookable_resource` (
    `bookable_id` int(11) UNSIGNED AUTO_INCREMENT,
    `emp_number` int(7) NOT NULL DEFAULT 0,
    `is_active` smallint DEFAULT 0,
    PRIMARY KEY(`bookable_id`)
) ENGINE = INNODB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ohrm_booking` (
    `booking_id` int(11) UNSIGNED AUTO_INCREMENT,
    `bookable_id` int(11) UNSIGNED NOT NULL,
    `customer_id` int(11) NOT NULL,
    `project_id` int(11) NOT NULL,
    `duration` float(18, 2) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `start_time` TIME NULL DEFAULT NULL,
    `end_time` TIME NULL DEFAULT NULL,
    `booking_color` VARCHAR(8) NOT NULL,
    `available_on` VARCHAR(16) NOT NULL,
    PRIMARY KEY(`booking_id`)
) ENGINE = INNODB DEFAULT CHARSET=utf8;

ALTER TABLE `ohrm_bookable_resource`
    ADD FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee`(`emp_number`);

ALTER TABLE `ohrm_booking`
    ADD FOREIGN KEY (`bookable_id`) REFERENCES `ohrm_bookable_resource`(`bookable_id`),
    ADD FOREIGN KEY (`project_id`)  REFERENCES `ohrm_project`(`project_id`),
    ADD FOREIGN KEY (`customer_id`) REFERENCES `ohrm_customer`(`customer_id`);

-- Plugin settings
INSERT INTO hs_hr_config (`key`, `value`) VALUES
('booking.company_breaks_time', 0);

-- Plugin setup
INSERT INTO ohrm_module (`name`, `status`) VALUES
('booking', 1);
SET @booking_module_id := (SELECT LAST_INSERT_ID());

-- Screens
INSERT INTO ohrm_screen (`name`, `module_id`, `action_url`) VALUES
('Configure Booking', @booking_module_id, 'configureBooking'),
('Bookable Resources', @booking_module_id, 'viewBookableResources'),
('Add Bookable Resource', @booking_module_id, 'addBookableResource'),
('Bookings', @booking_module_id, 'viewBookings'),
('Add Booking', @booking_module_id, 'addBooking'),
('My Schedule', @booking_module_id, 'viewMyBookings');

-- Menus
INSERT INTO ohrm_menu_item (`menu_title`, `screen_id`, `parent_id`, `level`, `order_hint`, `url_extras`, `status`) VALUES
('Booking', NULL, NULL, 1, 1100, NULL, 1),
('My Schedule', @view_my_booking_screen_id, NULL, 1, 1100, NULL, 1);

SET @booking_menu_id := (SELECT `id` FROM ohrm_menu_item WHERE `menu_title` = 'Booking');

INSERT INTO ohrm_menu_item (`menu_title`, `screen_id`, `parent_id`, `level`, `order_hint`, `url_extras`, `status`) VALUES
('Configuration', @configure_screen_id, @booking_menu_id, 2, 100, NULL, 1),
('Bookable Resources', @view_bookable_rs_screen_id, @booking_menu_id, 2, 200, NULL, 1),
('Add Bookable Resource', @add_bookable_rs_screen_id, @booking_menu_id, 2, 300, NULL, 1),
('Bookings', @view_bookings_screen_id, @booking_menu_id, 2, 400, NULL, 1),
('Add Booking', @add_booking_screen_id, @booking_menu_id, 2, 500, NULL, 1);

-- Permissions
INSERT INTO `ohrm_data_group` (`name`, `description`, `can_read`, `can_create`, `can_update`, `can_delete`) VALUES
('booking_configuration', 'Booking - Configuration', 1, 1, 1, NULL);
SET @booking_data_group := (SELECT LAST_INSERT_ID());

INSERT INTO `ohrm_data_group_screen`(`data_group_id`, `screen_id`, `permission`) VALUES
(@booking_data_group, @configure_screen_id, 1),
(@booking_data_group, @configure_screen_id, 2),
(@booking_data_group, @configure_screen_id, 3);

-- Roles
SET @admin_role_id := (SELECT `id` FROM ohrm_user_role WHERE `name` = 'Admin'); 
INSERT INTO `ohrm_user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`, `self`) VALUES
(@admin_role_id, @booking_data_group, 1, 1, 1, NULL, 0);

INSERT INTO ohrm_user_role_screen (`user_role_id`, `screen_id`, `can_read`, `can_create`, `can_update`, `can_delete`) VALUES  
(@admin_role_id, @configure_screen_id, 1, 1, 1, 0);
