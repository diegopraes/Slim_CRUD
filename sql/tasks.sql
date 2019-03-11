

CREATE TABLE tasks(
  id INT NOT NULL,
  task VARCHAR(200) NOT NULL,
  status TINYINT NOT NULL DEFAULT '1',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE tasks ADD PRIMARY KEY (id);
ALTER TABLE tasks MODIFY id INT NOT NULL AUTO_INCREMENT;


INSERT INTO tasks(id, task, status, created_at) VALUES
(1, 'Find Bugs', 1, '2019-03-01 19:54:40'),
(2, 'Review Code', 1, '2018-04-01 13:54:40'),
(3, 'Fix Bugs', 1, '2017-03-01 12:14:40'),
(4, 'Refactor Code', 1, '2016-03-01 11:24:40'),
(5, 'Push to Prod', 1, '2015-03-01 20:34:40');
