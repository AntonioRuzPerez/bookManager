-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: database:3306
-- Versión del servidor: 8.4.6
-- Versión de PHP: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de datos: `libGestorDB`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Book`
--

CREATE TABLE `Book` (
                        `id` int NOT NULL,
                        `title` varchar(250) NOT NULL,
                        `author` varchar(250) NOT NULL,
                        `isbn` varchar(13) NOT NULL,
                        `year` int NOT NULL,
                        `summary` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Disparadores `Book`
--
DELIMITER $$
CREATE TRIGGER `after_book_delete` AFTER DELETE ON `Book` FOR EACH ROW BEGIN
    INSERT INTO BookChangeLog (book_id, operation_type, old_title, old_author, old_isbn, old_year, old_summary)
    VALUES (OLD.id, 'DELETE', OLD.title, OLD.author, OLD.isbn, OLD.year, OLD.summary);
END
    $$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_book_update` AFTER UPDATE ON `Book` FOR EACH ROW BEGIN
    INSERT INTO BookChangeLog (book_id, operation_type, old_title, old_author, old_isbn, old_year, old_summary)
    VALUES (NEW.id, 'UPDATE', OLD.title, OLD.author, OLD.isbn, OLD.year, OLD.summary);
END
    $$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BookChangeLog`
--

CREATE TABLE `BookChangeLog` (
                                 `id` int NOT NULL,
                                 `book_id` int DEFAULT NULL,
                                 `operation_type` enum('INSERT','UPDATE','DELETE') DEFAULT NULL,
                                 `old_title` varchar(250) DEFAULT NULL,
                                 `old_author` varchar(250) DEFAULT NULL,
                                 `old_isbn` varchar(13) DEFAULT NULL,
                                 `old_year` int DEFAULT NULL,
                                 `old_summary` text,
                                 `change_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Book`
--
ALTER TABLE `Book`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbnUnique` (`isbn`),
  ADD KEY `idx_author` (`author`),
  ADD KEY `idx_year` (`year`),
  ADD KEY `idx_author_title` (`author`,`title`);

--
-- Indices de la tabla `BookChangeLog`
--
ALTER TABLE `BookChangeLog`
    ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Book`
--
ALTER TABLE `Book`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `BookChangeLog`
--
ALTER TABLE `BookChangeLog`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `BookChangeLog`
--
ALTER TABLE `BookChangeLog`
    ADD CONSTRAINT `BookChangeLog_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `Book` (`id`);
COMMIT;

