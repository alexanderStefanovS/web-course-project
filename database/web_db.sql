-----------------------------------------------------
-- Table `web_course_project_db`.`roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `web_course_project_db`.`roles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `web_course_project_db`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `web_course_project_db`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `firstname` VARCHAR(45) NOT NULL,
  `lastname` VARCHAR(45) NOT NULL,
  `roles_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) VISIBLE,
  INDEX `fk_users_roles1_idx` (`roles_id` ASC) VISIBLE,
  CONSTRAINT `fk_users_roles1`
    FOREIGN KEY (`roles_id`)
    REFERENCES `web_course_project_db`.`roles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `web_course_project_db`.`subjects`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `web_course_project_db`.`subjects` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `web_course_project_db`.`users_subjects`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `web_course_project_db`.`users_subjects` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT NOT NULL,
  `subjects_id` INT NOT NULL,
  `course` VARCHAR(45) NOT NULL,
  `specialty` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_users_has_subjects_subjects1_idx` (`subjects_id` ASC) VISIBLE,
  INDEX `fk_users_has_subjects_users1_idx` (`users_id` ASC) VISIBLE,
  CONSTRAINT `fk_users_has_subjects_users1`
    FOREIGN KEY (`users_id`)
    REFERENCES `web_course_project_db`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_has_subjects_subjects1`
    FOREIGN KEY (`subjects_id`)
    REFERENCES `web_course_project_db`.`subjects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `web_course_project_db`.`halls`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `web_course_project_db`.`halls` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `number` VARCHAR(5) NOT NULL,
  `floor` INT NOT NULL,
  `type` VARCHAR(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `number_UNIQUE` (`number` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `web_course_project_db`.`halls_schedule`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `web_course_project_db`.`halls_schedule` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `hour` INT NOT NULL,
  `users_subjects_id` INT NOT NULL,
  `halls_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_halls_schedule_users_subjects1_idx` (`users_subjects_id` ASC) VISIBLE,
  INDEX `fk_halls_schedule_halls1_idx` (`halls_id` ASC) VISIBLE,
  CONSTRAINT `fk_halls_schedule_users_subjects1`
    FOREIGN KEY (`users_subjects_id`)
    REFERENCES `web_course_project_db`.`users_subjects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_halls_schedule_halls1`
    FOREIGN KEY (`halls_id`)
    REFERENCES `web_course_project_db`.`halls` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

