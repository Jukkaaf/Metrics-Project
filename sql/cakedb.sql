CREATE TABLE users (
  id int(10) NOT NULL auto_increment PRIMARY KEY,
  account varchar(20) NOT NULL,
  password varchar(20) NOT NULL,
  first_name varchar(20),
  last_name varchar(20),
  email varchar(40) NOT NULL,
  phone varchar(15),
  role int(2) NOT NULL,
  UNIQUE KEY (account),
  UNIQUE KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE metric_types (
  mtype int(10) NOT NULL auto_increment,
  description varchar(200) NOT NULL,
  PRIMARY KEY (mtype)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE projects (
  id int(10) NOT NULL auto_increment PRIMARY KEY,
  project_name varchar(50) NOT NULL,
  created_on date NOT NULL,
  updated_on date,
  finished_date date,
  status varchar(30),
  description varchar(100),
  is_public tinyint(1) NOT NULL,
  UNIQUE KEY (project_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE metrics (
  id int(10) NOT NULL auto_increment PRIMARY KEY,
  project_id int(10) NOT NULL,
  metric_type int(10) NOT NULL,
  date date NOT NULL,
  metric_value float NOT NULL,
  FOREIGN KEY project_key (project_id) REFERENCES projects (id),
  FOREIGN KEY metric_key (metric_type) REFERENCES metric_types (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE members (
  id int(10) NOT NULL auto_increment PRIMARY KEY,
  user_id int(10) NOT NULL,
  project_id int(10) NOT NULL,
  project_role int(2) NOT NULL,
  starting_date date,
  ending_date date,
  FOREIGN KEY user_key (user_id) REFERENCES users (id),
  FOREIGN KEY project_key (project_id) REFERENCES projects (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE works (
  id int(10) NOT NULL PRIMARY KEY,
  member_id int(10) NOT NULL,
  date date NOT NULL,
  description varchar(100) NOT NULL,
  hours float NOT NULL,
  type int(2),
  FOREIGN KEY member_key (member_id) REFERENCES members (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE weekly_reports (
  id int(10) NOT NULL PRIMARY KEY,
  project_id int(10) NOT NULL,
  title varchar(50) NOT NULL,
  date date NOT NULL,
  reqlink varchar(100),
  problems varchar(200),
  meetings varchar(200) NOT NULL,
  additional varchar(200),
  FOREIGN KEY project_key (project_id) REFERENCES projects (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE requirements (
  id int(10) NOT NULL PRIMARY KEY,
  changenum int(10) NOT NULL,
  project_id int(10) NOT NULL,
  name varchar(50),
  description varchar(500) NOT NULL,
  status int(2) NOT NULL,
  version int(2) NOT NULL,
  date date NOT NULL,
  FOREIGN KEY project_key (project_id) REFERENCES projects (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;