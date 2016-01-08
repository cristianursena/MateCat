<?php

use Phinx\Migration\AbstractMigration;

class CreateQaJobReviews extends AbstractMigration {

    public $sql_up = <<<EOF
CREATE TABLE `qa_job_reviews` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_project` int(11) NOT NULL,
    `id_job` bigint(20) NOT NULL,
    `password` varchar(45) NOT NULL,
    `review_password` varchar(45) NOT NULL,
    `score` bigint(20) NOT NULL DEFAULT 0,
    `num_errors` int(11) NOT NULL DEFAULT 0,
    `is_pass` tinyint(4) NOT NULL DEFAULT 0,
    `force_pass_at` timestamp NULL DEFAULT NULL ,
    PRIMARY KEY (`id`),
    KEY `id_project` (`id_project`),
    UNIQUE KEY `id_job_password` (`id_job`, `password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOF;

    public $sql_down = <<<EOF
DROP TABLE `qa_job_reviews`;
EOF;

    public function up() {
        $this->execute($this->sql_up);
    }

    public function down() {
        $this->execute($this->sql_down);
    }

}
