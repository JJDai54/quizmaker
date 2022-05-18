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
  `cat_description` text NOT NULL,
  `cat_theme` varchar(50) NOT NULL DEFAULT '0',
  `cat_weight` int(11) NOT NULL DEFAULT '0',
  `cat_creation` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000',
  `cat_update` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB;

#
# Structure table for `quizmaker_quiz` 20
#

CREATE TABLE `quizmaker_quiz` (
  `quiz_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz_flag` int(8) NOT NULL,
  `quiz_cat_id` int(10) NOT NULL DEFAULT '0',
  `quiz_name` varchar(255) NOT NULL DEFAULT '',
  `quiz_author` varchar(80) NOT NULL DEFAULT '',
  `quiz_fileName` varchar(80) NOT NULL,
  `quiz_description` text NOT NULL,
  `quiz_weight` int(11) NOT NULL DEFAULT '0',  
  `quiz_attempts` int(8) NOT NULL DEFAULT '0',
  `quiz_publishQuiz` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_publishResults` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_publishAnswers` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_dateBegin` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000',
  `quiz_dateEnd` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000',
  `quiz_onClickSimple` tinyint(1) NOT NULL DEFAULT '1',
  `quiz_answerBeforeNext` tinyint(1) NOT NULL DEFAULT '1',
  `quiz_allowedPrevious` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_allowedSubmit` tinyint(1) NOT NULL DEFAULT '1',
  `quiz_useTimer` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_showResultAllways` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_showReponsesBottom` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_showLog` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_legend` text NOT NULL,
  `quiz_dateBeginOk` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_dateEndOk` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_theme` varchar(50) NOT NULL,
  `quiz_shuffleQuestions` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_showGoodAnswers` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_showBadAnswers` int(1) NOT NULL DEFAULT '0',
  `quiz_showReloadAnswers` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_minusOnShowGoodAnswers` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_showResultPopup` tinyint(1) NOT NULL DEFAULT '0',
  `quiz_showTypeQuestion` tinyint(4) NOT NULL DEFAULT '0',
  `quiz_build` int(10) NOT NULL DEFAULT '0',
  `quiz_actif` tinyint(1) NOT NULL DEFAULT '1',
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
  `quest_flag` int(8) NOT NULL,
  `quest_quiz_id` int(10) NOT NULL DEFAULT '0',
  `quest_question` varchar(255) NOT NULL DEFAULT '',
  `quest_comment1` text NOT NULL,
  `quest_type_question` varchar(30) NOT NULL DEFAULT '',
  `quest_type_form` tinyint(1) NOT NULL DEFAULT '0',
  `quest_explanation` text NOT NULL,
  `quest_learn_more` varchar(255) NOT NULL DEFAULT '',
  `quest_see_also` varchar(255) NOT NULL DEFAULT '',
  `quest_minReponse` tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
  `quest_creation` datetime(6) DEFAULT '0000-00-00 00:00:00.000000',
  `quest_update` datetime(6) DEFAULT '0000-00-00 00:00:00.000000',
  `quest_comment2` text NOT NULL,
  `quest_weight` int(11) NOT NULL DEFAULT '0',
  `quest_isQuestion` int(1) NOT NULL DEFAULT '1',
  `quest_timer` int(11) NOT NULL DEFAULT '0',
  `quest_options` varchar(50) NOT NULL,
  `quest_shuffleAnswers` tinyint(4) NOT NULL DEFAULT '0',
  `quest_numbering` tinyint(1) NOT NULL DEFAULT '0',
  `quest_visible` tinyint(1) NOT NULL DEFAULT '1',
  `quest_actif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`quest_id`)
) ENGINE=InnoDB;

#
# Structure table for `quizmaker_answers` 5
#

CREATE TABLE `quizmaker_answers` (
  `answer_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `answer_flag` int(8) NOT NULL,
  `answer_quest_id` int(10) NOT NULL DEFAULT '0',
  `answer_caption` varchar(255) NOT NULL DEFAULT '',
  `answer_proposition` text NOT NULL,
  `answer_points` varchar(255) NOT NULL DEFAULT '',
  `answer_weight` int(11) NOT NULL DEFAULT '0',
  `answer_inputs` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`answer_id`)
) ENGINE=InnoDB;

#
# Structure table for `quizmaker_type_question` 4
#

CREATE TABLE `quizmaker_type_question` (
  `type_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) NOT NULL DEFAULT '',
  `type_description` text NOT NULL,
  `type_shortdesc` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB;

#
# Structure table for `quizmaker_messages` 3
#

CREATE TABLE `quizmaker_messages` (
  `msg_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `msg_code` varchar(30) NOT NULL DEFAULT '',
  `msg_constant` varchar(30) NOT NULL DEFAULT '',
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
  `result_uname` varchar(50) NOT NULL,
  `result_ip` varchar(50) NOT NULL,
  `result_score_achieved` int(8) NOT NULL DEFAULT '0',
  `result_score_max` int(8) NOT NULL DEFAULT '0',
  `result_score_min` int(8) NOT NULL DEFAULT '0',
  `result_answers_achieved` int(8) NOT NULL DEFAULT '0',
  `result_answers_total` int(8) NOT NULL DEFAULT '0',
  `result_duration` int(8) NOT NULL DEFAULT '0',
  `result_note` float NOT NULL DEFAULT '0',
  `result_creation` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000',
  `result_update` datetime(6) NOT NULL,
  `result_attempts` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`result_id`)
) ENGINE=InnoDB;


INSERT INTO quizmaker_categories( `cat_name`, `cat_description`,  `cat_theme`, `cat_weight`) VALUES 
('Test', 'Catégorie de test', 'default', 0);


INSERT INTO quizmaker_messages( `msg_code`, `msg_constant`) VALUES 
('message01', 'MESSAGE01'),
('results', 'RESULTS'),
('sortCombobox', 'SORTCOMBOBOX'),
('radio', 'RADIO'),
('checkbox', 'CHECKBOX'),
('textbox', 'TEXTBOX'),
('multiTextbox', 'MULTITEXTBOX'),
('allType', 'ALLTYPE'),
('forPoints', 'FORPOINTS'),
('forChrono', 'FORCHRONO'),
('forPointsTimer', 'FORPOINTSTIMER'),
('resultOnSlide', 'RESULTONSLIDE'),
('btnNext', 'BTNNEXT'),
('btnPrevious', 'BTNPREVIOUS'),
('btnSubmit', 'BTNSUBMIT'),
('btnContinue', 'BTNCONTINUE'),
('btnReload', 'BTNRELOAD'),
('btnAntiseche', 'BTNANTISECHE'),
('showReponses', 'SHOWREPONSES'),
('btnReloadList', 'BTNRELOADLIST'),
('btnReloadText', 'BTNRELOADTEXT'),
('resultBravo0 ', 'RESULTBRAVO0'),
('resultBravo1 ', 'RESULTBRAVO1'),
('resultBravo2 ', 'RESULTBRAVO2'),
('resultBravo3 ', 'RESULTBRAVO3'),
('resultScore ', 'RESULTSCORE'),
('points ', 'POINTS'),
('bonneReponse ', 'BONNEREPONSE'),
('tplWord ', 'TPLWORD'),
('tplWord2 ', 'TPLWORD2'),
('tplReponseTable ', 'TPLREPONSETABLE'),
('tplReponseDblTable ', 'TPLREPONSEDBLTABLE'),
('tplReponseDblTD ', 'TPLREPONSEDBLTD'),
('tplReponseTD ', 'TPLREPONSETD');
