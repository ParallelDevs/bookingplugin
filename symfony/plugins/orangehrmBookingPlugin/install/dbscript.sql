/**
 * Author:  amora
 * Created: Oct 5, 2016
 */

-- DB Schema for plugin
CREATE TABLE `ohrm_bookable_resource` (
    `bookable_id` int(11) UNSIGNED AUTO_INCREMENT,
    `emp_number` int(7) NOT NULL DEFAULT 0,
    `is_active` smallint DEFAULT 0,    
    PRIMARY KEY(`bookable_id`)
) ENGINE = INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `ohrm_booking` (
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
    `available_on` LONGTEXT NOT NULL,
    PRIMARY KEY(`booking_id`)
) ENGINE = INNODB DEFAULT CHARSET=utf8;

ALTER TABLE `ohrm_bookable_resource` 
    ADD FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee`(`emp_number`);

ALTER TABLE `ohrm_booking`
    ADD FOREIGN KEY (`bookable_id`) REFERENCES `ohrm_bookable_resource`(`bookable_id`),
    ADD FOREIGN KEY (`project_id`)  REFERENCES `ohrm_project`(`project_id`),
    ADD FOREIGN KEY (`customer_id`) REFERENCES `ohrm_customer`(`customer_id`);

-- Plugin settings
INSERT INTO `hs_hr_config` (`key`,`value`) VALUES
('booking.company_breaks_time','');

INSERT INTO `ohrm_module` (`name`, `status`) VALUES ('booking', 1);

/** Module setup for Admin role **/
SET @booking_module_id := (SELECT `id` FROM `ohrm_module` WHERE name = 'booking' LIMIT 1);


-- Module screens
INSERT INTO `ohrm_screen` (`name`, `module_id`, `action_url`) VALUES
('Configure Booking', @booking_module_id, 'configureBooking'),
('Bookable Resources', @booking_module_id, 'viewBookableResources'),
('Add Bookable Resource', @booking_module_id, 'addBookableResource'),
('Bookings', @booking_module_id, 'viewBookings'),
('Add Booking', @booking_module_id, 'addBooking');

SET @configure_screen_id := (SELECT `id` FROM `ohrm_screen` WHERE `name` = 'Configure Booking' LIMIT 1);
SET @view_bookable_rs_screen_id := (SELECT `id` FROM `ohrm_screen` WHERE `name` = 'Bookable Resources' LIMIT 1);
SET @add_bookable_rs_screen_id := (SELECT `id` FROM `ohrm_screen` WHERE `name` = 'Add Bookable Resource' LIMIT 1);
SET @view_bookings_screen_id := (SELECT `id` FROM `ohrm_screen` WHERE name = 'Bookings' LIMIT 1);
SET @add_booking_screen_id := (SELECT `id` FROM `ohrm_screen` WHERE `name` = 'Add Booking' LIMIT 1);

-- Module Menus
INSERT INTO `ohrm_menu_item` (`menu_title`, `screen_id`, `parent_id`, `level`, `order_hint`, `url_extras`, `status`) VALUES
('Booking', NULL, NULL, 1, 1100, '', 1);

SET @booking_menu_id := (SELECT `id` FROM `ohrm_menu_item` WHERE `menu_title` = 'Booking' AND `level` = 1);

INSERT INTO `ohrm_menu_item` (`menu_title`, `screen_id`, `parent_id`, `level`, `order_hint`, `url_extras`, `status`) VALUES
('Configuration', @configure_screen_id, @booking_menu_id, 2, 100, '', 1),
('Bookable Resources', @view_bookable_rs_screen_id, @booking_menu_id, 2, 200, '', 1),
('Add Bookable Resource', @add_bookable_rs_screen_id, @booking_menu_id, 2, 300, '', 1),
('Bookings', @view_bookings_screen_id, @booking_menu_id, 2, 400, '', 1),
('Add Booking', @add_booking_screen_id, @booking_menu_id, 2, 500, '', 1);

-- Add access to Admin role
SET @admin_user_role_id := (SELECT `id` FROM `ohrm_user_role` WHERE `name` = 'Admin' LIMIT 1);

INSERT INTO `ohrm_user_role_screen` (`user_role_id`, `screen_id`, `can_read`, `can_create`, `can_update`, `can_delete`) VALUES
(@admin_user_role_id, @configure_screen_id, 1, 1, 1, 0),
(@admin_user_role_id, @view_bookable_rs_screen_id, 1, 1, 1, 0),
(@admin_user_role_id, @add_bookable_rs_screen_id, 1, 1, 1, 0),
(@admin_user_role_id, @view_bookings_screen_id, 1, 1, 1, 0),
(@admin_user_role_id, @add_booking_screen_id, 1, 1, 1, 0);

/** Module setup for ESS role **/
SET @booking_module_id := (SELECT `id` FROM `ohrm_module` WHERE name = 'booking' LIMIT 1);

-- Module screens
INSERT INTO `ohrm_screen` (`name`, `module_id`, `action_url`) VALUES
('My Bookings',@booking_module_id,'viewMyBookings');

SET @view_my_booking_screen_id := (SELECT `id` FROM `ohrm_screen` WHERE `name` = 'My Bookings' LIMIT 1);

-- Module Menus
INSERT INTO `ohrm_menu_item` (`menu_title`, `screen_id`, `parent_id`, `level`, `order_hint`, `url_extras`, `status`) VALUES
('My Schedule', @view_my_booking_screen_id, NULL, 1, 1100, '', 1);

SET @my_booking_menu_id := (SELECT `id` FROM `ohrm_menu_item` WHERE `menu_title` = 'My Schedule' AND `level` = 1);

-- Add access to ESS role
SET @ess_user_role_id := (SELECT `id` FROM `ohrm_user_role` WHERE `name` = 'ESS' LIMIT 1);

INSERT INTO `ohrm_user_role_screen` (`user_role_id`, `screen_id`, `can_read`, `can_create`, `can_update`, `can_delete`) VALUES
(@ess_user_role_id, @view_my_booking_screen_id, 1, 0, 0, 0);