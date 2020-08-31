<?php
include_once("config.php");
$SQL = "DROP TABLE IF EXISTS `idf`;
    CREATE TABLE IF NOT EXISTS `idf` (
    `idfid` int(11) NOT NULL,
    `idfstring` varchar(4) NOT NULL,
    `idfnumber` varchar(50) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ALTER TABLE `idf` ADD PRIMARY KEY (`idfid`);
    ALTER TABLE `idf` MODIFY `idfid` int(11) NOT NULL AUTO_INCREMENT;

    DROP TABLE IF EXISTS `search`;
    CREATE TABLE IF NOT EXISTS `search` (
    `SearchId` int(11) NOT NULL,
    `SearchString` varchar(100) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ALTER TABLE `search` ADD PRIMARY KEY (`SearchId`);
    ALTER TABLE `search` MODIFY `SearchId` int(11) NOT NULL AUTO_INCREMENT;

    DROP TABLE IF EXISTS `searchresult`;
    CREATE TABLE IF NOT EXISTS `searchresult` (
    `SearchResultId` int(11) NOT NULL,
    `SearchId` varchar(50) NOT NULL,
    `WhiteListId` int(11) NOT NULL,
    `SearchResultRate` int(11) NOT NULL,
    `Link` varchar(1000) NOT NULL,
    `Title` varchar(1000) NOT NULL,
    `Content` longtext NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ALTER TABLE `searchresult` ADD PRIMARY KEY (`SearchResultId`);
    ALTER TABLE `searchresult` MODIFY `SearchResultId` int(11) NOT NULL AUTO_INCREMENT;
    ";
$conn->exec($SQL);
echo "Success";
?>