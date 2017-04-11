CREATE TABLE `flickr__photo` (
  `id` int(11) UNSIGNED NOT NULL,
  `photo_set_id` int(11) NOT NULL,
  `thumb_url` varchar(255) DEFAULT NULL,
  `thumb_width` int(11) DEFAULT NULL,
  `thumb_height` int(11) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `photo_width` int(11) DEFAULT NULL,
  `photo_height` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(1024) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `flickr__photo_set` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `flickr__photo`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `flickr__photo_set`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `flickr__photo`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `flickr__photo_set`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;