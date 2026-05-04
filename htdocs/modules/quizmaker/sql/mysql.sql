# SQL Dump for quizmaker module
# PhpMyAdmin Version: 4.0.4
# http://www.phpmyadmin.net
#
# Host: xmodules.jubile.fr
# Generated on: Thu Aug 27, 2020 to 12:19:50
# Server version: 5.6.45-log
# PHP Version: 7.3.21

#
# Structure table for `quizmaker_categories` 6
#

CREATE TABLE `quizmaker_categories` (
  `cat_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cat_parent_id` int(8) NOT NULL DEFAULT '0',
  `cat_name` varchar(255) NOT NULL DEFAULT '',
  `cat_actif`  tinyint(1) NOT NULL DEFAULT '1', 
  `cat_description` text NOT NULL,
  `cat_readme_text` LONGTEXT NOT NULL,
  `cat_readme_status` TINYINT NOT NULL DEFAULT '0',
  `cat_readme_label` varchar(80) NOT NULL DEFAULT '',
  `cat_image` varchar(255) NOT NULL DEFAULT '0',
  `cat_theme` varchar(50) NOT NULL DEFAULT '0',
  `cat_max_attempts` INT NOT NULL DEFAULT '1',   
  `cat_delai_cookie` INT NOT NULL DEFAULT '3600',   
  `cat_weight` int(11) NOT NULL DEFAULT '0',
  `cat_creation` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000',
  `cat_update` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB;

#
# Structure table for `quizmaker_readme` 6
#

CREATE TABLE `quizmaker_readme` (
  `readme_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `readme_cat_id` int(8) NOT NULL DEFAULT '0',
  `readme_uid` int(8) NOT NULL DEFAULT '0',
  `readme_email` varchar(60) NOT NULL DEFAULT '',
  `readme_count` int(8) NOT NULL DEFAULT '0',
  `readme_creation` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000',
  `readme_update` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000',
  PRIMARY KEY (`readme_id`),
  KEY `quizmaker_readme_uid` (`readme_cat_id`, `readme_uid`)
) ENGINE=InnoDB;

#
# Structure table for `quizmaker_quiz` 20
#

CREATE TABLE `quizmaker_quiz` (
  `quiz_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz_flag` int NOT NULL,
  `quiz_flag_text`  varchar(255) NOT NULL DEFAULT '',
  `quiz_cat_id` int NOT NULL DEFAULT '0',
  `quiz_name` varchar(255) NOT NULL DEFAULT '',
  `quiz_difficulty` TINYINT NOT NULL DEFAULT '0', 
  `quiz_subject` varchar(80) NOT NULL DEFAULT '',
  `quiz_author` varchar(80) NOT NULL DEFAULT '',
  `quiz_fileName` varchar(80) NOT NULL,
  `quiz_folderJS` varchar(80) NOT NULL,
  `quiz_description` text NOT NULL,
  `quiz_weight` int NOT NULL DEFAULT '0',
  `quiz_attempts` int NOT NULL DEFAULT '0',
  `quiz_publishQuiz` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_publishResults` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_publishAnswers` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_questPosComment1` tinyint NOT NULL DEFAULT '1',
  `quiz_showConsigne` tinyint(1) NOT NULL DEFAULT '1',
  `quiz_showTimer` tinyint(1) NOT NULL DEFAULT '1',
  `quiz_timerSize` tinyint(1) NOT NULL DEFAULT '1',
  `quiz_dateBegin` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000',
  `quiz_dateEnd` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000',
  `quiz_dateBeginOk` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_dateEndOk` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_legend` text NOT NULL,
  `quiz_theme` varchar(50) NOT NULL,
  `quiz_image` varchar(255) NOT NULL,
  `quiz_background` varchar(255) NOT NULL,
  `quiz_build` int NOT NULL DEFAULT '0',
  `quiz_execution` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_libBegin` varchar(120) NOT NULL,
  `quiz_libEnd` varchar(120) NOT NULL,
  `quiz_optionsIhm` bit(16) NOT NULL DEFAULT b'0',
  `quiz_optionsDev` bit(16) NOT NULL DEFAULT b'0',
  `quiz_actif` tinyint(1) NOT NULL DEFAULT '1',
  `quiz_max_attempts` INT NOT NULL DEFAULT '0',   
  `quiz_delai_cookie` INT NOT NULL DEFAULT '3600',   
  `quiz_creation` datetime(6) DEFAULT '0000-00-00 00:00:00.000000',
  `quiz_update` datetime(6) DEFAULT '0000-00-00 00:00:00.000000',
  PRIMARY KEY (`quiz_id`)
) ENGINE=InnoDB;


#
# Structure table for `quizmaker_questions` 9
#

CREATE TABLE `quizmaker_questions` (
  `quest_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `quest_parent_id` int(11) NOT NULL DEFAULT '0',
  `quest_reference_id` int(11) NOT NULL DEFAULT '0',
  `quest_flag` int(8) NOT NULL,
  `quest_quiz_id` int(10) NOT NULL DEFAULT '0',
  `quest_question` varchar(255) NOT NULL DEFAULT '',
  `quest_question_style` text NOT NULL,
  `quest_identifiant1` varchar(50) NOT NULL DEFAULT '',
  `quest_identifiant2` varchar(50) NOT NULL DEFAULT '',
  `quest_comment1` text NOT NULL,
  `quest_posComment1` tinyint(1) NOT NULL DEFAULT '0',
  `quest_plugin` varchar(30) NOT NULL DEFAULT '',
  `quest_variant` varchar(30) NOT NULL DEFAULT '',
  `quest_explanation` text NOT NULL,
  `quest_explanation_img` varchar(255) NOT NULL DEFAULT '', 
  `quest_explanation_style` text NOT NULL,
  `quest_consigne` text NOT NULL,
  `quest_learn_more` varchar(255) NOT NULL DEFAULT '',
  `quest_see_also` varchar(255) NOT NULL DEFAULT '',
  `quest_image` varchar(255) NOT NULL DEFAULT '',
  `quest_height` int(11) NOT NULL DEFAULT '80',
  `quest_shadow` varchar(10) NOT NULL DEFAULT '#00000',
  `quest_image_style` text NOT NULL,
  `quest_zoom` tinyint(1) NOT NULL DEFAULT '0',
  `quest_background` varchar(255) NOT NULL DEFAULT '',
  `quest_points`  tinyint(1) NOT NULL DEFAULT '0',
  `quest_comment2` text NOT NULL,
  `quest_weight` int(11) NOT NULL DEFAULT '0',
  `quest_timer` int(11) NOT NULL DEFAULT '0',
  `quest_options` varchar(2048) NOT NULL,
  `quest_numbering` tinyint(1) NOT NULL DEFAULT '0',
  `quest_start_timer` tinyint(1) NOT NULL DEFAULT '0',
  `quest_shuffleAnswers` tinyint(4) NOT NULL DEFAULT '0',
  `quest_visible` tinyint(1) NOT NULL DEFAULT '1',
  `quest_isQuestion` int(1) NOT NULL DEFAULT '1',
  `quest_actif` tinyint(1) NOT NULL DEFAULT '1',
  `quest_creation` datetime(6) DEFAULT '0000-00-00 00:00:00.000000',
  `quest_update` datetime(6) DEFAULT '0000-00-00 00:00:00.000000',
  PRIMARY KEY (`quest_id`)
) ENGINE=InnoDB;

#
# Structure table for `quizmaker_answers` 5
#

CREATE TABLE `quizmaker_answers` (
  `answer_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `answer_flag` int(8) NOT NULL,
  `answer_quest_id` int(10) NOT NULL DEFAULT '0',
  `answer_proposition` text NOT NULL,
  `answer_caption` varchar(255) NOT NULL DEFAULT '',
  `answer_background` varchar(30) NOT NULL DEFAULT '',
  `answer_color` varchar(30) NOT NULL DEFAULT '',
  `answer_points` int(8) NOT NULL DEFAULT '0',
  `answer_weight` int(11) NOT NULL DEFAULT '0',
  `answer_inputs` int(10) NOT NULL DEFAULT '1',
  `answer_buffer` VARCHAR(2048) NOT NULL DEFAULT '',  
  `answer_image1` VARCHAR(255) NOT NULL DEFAULT '',  
  `answer_image2` VARCHAR(255) NOT NULL DEFAULT '',  
  `answer_group`  tinyint(1) NOT NULL DEFAULT '0', 
  PRIMARY KEY (`answer_id`)
) ENGINE=InnoDB;

#
# Structure table for `quizmaker_messages` 3
#

CREATE TABLE `quizmaker_messages` (
  `msg_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `msg_code` varchar(80) NOT NULL DEFAULT '',
  `msg_language` VARCHAR(50) NOT NULL DEFAULT '',
  `msg_message` VARCHAR(255) NOT NULL DEFAULT '', 
  `msg_editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`msg_id`)
) ENGINE=InnoDB;
#
# Structure table for `quizmaker_results` 9
#

CREATE TABLE `quizmaker_results` (
  `result_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `result_quiz_id` int(8) NOT NULL DEFAULT '0',
  `result_uid` int(8) NOT NULL DEFAULT '0',
  `result_uname` varchar(60) NOT NULL,
  `result_email` varchar(60) NOT NULL,
  `result_ip` varchar(50) NOT NULL,
  `result_attempts` int(8) NOT NULL DEFAULT '0',
  `result_score_achieved` int(8) NOT NULL DEFAULT '0',
  `result_score_max` int(8) NOT NULL DEFAULT '0',
  `result_score_min` int(8) NOT NULL DEFAULT '0',
  `result_answers_achieved` int(8) NOT NULL DEFAULT '0',
  `result_answers_total` int(8) NOT NULL DEFAULT '0',
  `result_duration` int(8) NOT NULL DEFAULT '0',
  `result_note` float NOT NULL DEFAULT '0',
  `result_creation` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000',
  `result_update` datetime(6) NOT NULL,
  PRIMARY KEY (`result_id`)
) ENGINE=InnoDB;

CREATE TABLE `quizmaker_cookies` (
  `cookie_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cookie_cat_id` int(8) NOT NULL DEFAULT '0',
  `cookie_quiz_id` int(8) NOT NULL DEFAULT '0',
  `cookie_uid` int(8) NOT NULL DEFAULT '0',
  `cookie_uname` varchar(25) NOT NULL,
  `cookie_readme` int(8) NOT NULL DEFAULT '0',
  `cookie_email` varchar(60) NOT NULL,
  `cookie_ip` varchar(50) NOT NULL,
  `cookie_attempts` int(8) NOT NULL DEFAULT '0',
  `cookie_dead_line` int(11) NOT NULL DEFAULT '0',
  `cookie_creation` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000',
  `cookie_update` datetime(6) NOT NULL,
  PRIMARY KEY (`cookie_id`),
  KEY `cookie_full` (`cookie_quiz_id`, `cookie_uid`, `cookie_email`)
) ENGINE=InnoDB;


#
# Structure table for `quizmaker_questions` 9
#

CREATE TABLE `quizmaker_options` (
  `opt_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `opt_name` varchar(30) NOT NULL DEFAULT '',
  `opt_actif`  tinyint(1) NOT NULL DEFAULT '1', 
  `opt_icone` varchar(30) NOT NULL DEFAULT '',
  `opt_optionsIhm` bit(16) NOT NULL DEFAULT b'0',
  `opt_optionsDev` bit(16) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`opt_id`)
) ENGINE=InnoDB;

ALTER TABLE quizmaker_categories ADD INDEX  `quizmaker_categories_01` (`cat_actif`,`cat_parent_id`,`cat_weight`);

ALTER TABLE quizmaker_quiz ADD UNIQUE `quizmaker_quiz_folderJS` (`quiz_folderJS`); 
ALTER TABLE quizmaker_quiz ADD INDEX `quizmaker_quiz_01` (`quiz_actif`, `quiz_weight`, `quiz_name`, `quiz_cat_id`); 
ALTER TABLE quizmaker_quiz ADD INDEX `quizmaker_quiz_02` (`quiz_actif`, `quiz_cat_id`, `quiz_subject`); 
ALTER TABLE quizmaker_quiz ADD INDEX  `quizmaker_quiz_flag` (`quiz_flag`); 
ALTER TABLE quizmaker_quiz ADD INDEX `quizmaker_quiz_periode` (`quiz_dateEnd`,`quiz_dateBegin`);

ALTER TABLE quizmaker_questions ADD INDEX  `quizmaker_quest_flag` (`quest_flag`); 
ALTER TABLE quizmaker_questions ADD INDEX  `quizmaker_quest_01` (`quest_actif`,`quest_quiz_id`,`quest_weight`); 
ALTER TABLE quizmaker_questions ADD INDEX  `quizmaker_quest_weight` (`quest_weight`,`quest_actif`); 
ALTER TABLE quizmaker_questions ADD INDEX  `quizmaker_quest_quiz_id` (`quest_quiz_id`); 

ALTER TABLE quizmaker_answers ADD INDEX  `quizmaker_answer_flag` (`answer_flag`); 
ALTER TABLE quizmaker_answers ADD INDEX  `quizmaker_answer_01` (`answer_quest_id`,`answer_weight`,`answer_id`); 
 
ALTER TABLE quizmaker_results ADD INDEX  `quizmaker_results_result_quiz_id` (`result_quiz_id`);

INSERT INTO quizmaker_options (opt_id, opt_name, opt_actif, opt_icone, opt_optionsIhm, opt_optionsDev) VALUES
(1, 'Production simple', 1, 'binoption-01.png', b'0000000000000011', b'0000000000000000'),
(2, 'Production + timer', 0, 'binoption-02.png', b'0000000000100011', b'0000000000000000'),
(3, 'Production + timer + popup', 0, 'binoption-03.png', b'0000000010100011', b'0000000000000000'),
(4, 'Developpement', 1, 'binoption-04.png', b'0000000000010111', b'0000000001011111'),
(5, 'No options', 1, 'binoption-05.png', b'0000000000000000', b'0000000000000000'),
(6, 'All options', 1, 'binoption-06.png', b'0000000011111111', b'0000000011111111'),
(7, 'Personal 1', 0, 'binoption-07.png', b'0000000000100111', b'0000000000000000'),
(8, 'Personal 2', 0, 'binoption-08.png', b'0000000011110111', b'0000000000000000');


INSERT INTO quizmaker_categories(cat_id, cat_name, cat_actif, cat_description,  cat_theme, cat_weight) VALUES 
(1, 'Plugins QuizMaker', 'Catégorie de test', 1, 'default', 900),
(2, 'Quiz en préapaparation', 'Catégorie de test', 1, 'default', 992),
(3, 'Quiz à tester', 'Catégorie de test', 1, 'default', 994),
(4, 'Quiz à supprimer', 'Catégorie de test', 1, 'default', 996);

