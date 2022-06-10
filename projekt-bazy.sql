-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 10 Cze 2022, 18:25
-- Wersja serwera: 10.5.15-MariaDB-0ubuntu0.21.10.1
-- Wersja PHP: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `projekt-bazy`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `gry`
--

CREATE TABLE `gry` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(60) NOT NULL,
  `autor` varchar(60) DEFAULT NULL,
  `opis` text DEFAULT NULL,
  `foto` varchar(60) DEFAULT NULL,
  `data_wydania` date NOT NULL,
  `data` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `gry`
--

INSERT INTO `gry` (`id`, `nazwa`, `autor`, `opis`, `foto`, `data_wydania`, `data`) VALUES
(1, 'Minecraft', 'Mojang Studios', 'Minecraft – komputerowa gra survivalowa o otwartym świecie stworzona przez Markusa Perssona i rozwijana przez Mojang Studios. Minecraft pozwala graczom na budowanie i niszczenie obiektów położonych w losowo generowanym świecie gry. Gracz może atakować napotkane istoty, zbierać surowce czy wytwarzać przedmioty.', '/22.04/galeria/1logo.png', '2011-11-18', '2022-04-22'),
(2, 'Wiedźmin 3: Dziki Gon', 'CD Projekt Red', 'Wiedźmin 3: Dziki Gon (ang. The Witcher 3: Wild Hunt) – fabularna gra akcji wyprodukowana i wydana przez CD Projekt Red 19 maja 2015 na platformy Microsoft Windows, PlayStation 4 i Xbox One. Wersja na Nintendo Switch ukazała się 15 października 2019. Wydanie gry na PlayStation 5 i Xbox Series X/S zaplanowano na 2022 rok.', '/22.04/galeria/2logo.png', '2015-05-19', '2022-04-22'),
(3, 'Deathloop', 'Bethesda Softworks', 'DEATHLOOP to pierwszoosobowa strzelanka nowej generacji, stworzona przez Arkane Lyon – studio stojące za marką Dishonored. W DEATHLOOP dwójka rywalizujących ze sobą skrytobójców jest uwięziona w tajemniczej pętli czasu na wyspie Blackreef i skazana na powtarzanie w nieskończoność tego samego dnia. Wcielasz się w Colta, którego jedyną szansą na ucieczkę jest przerwanie cyklu poprzez wyeliminowanie ośmiu kluczowych celów przed nastąpieniem resetu dnia. Ucz się z każdego cyklu. Sprawdzaj nowe ścieżki, zbieraj informacje oraz szukaj nowej broni i umiejętności. Musisz zrobić wszystko, by przerwać pętlę.', '/22.04/galeria/3logo.webp', '2021-09-14', '2022-04-22'),
(5, 'Undertale', 'Toby Fox', 'Undertale (zapisywane jako UNDERTALE) – komputerowa gra fabularna, stworzona przez niezależnego twórcę gier komputerowych Toby’ego Foxa. Została wydana 15 września 2015 w angielskiej wersji językowej na platformy Microsoft Windows, macOS i Linux. 15 sierpnia 2017 pojawiła się wersja na konsole PlayStation 4 i PlayStation Vita, natomiast 18 września 2018 wydano wersję na konsolę Nintendo Switch. 16 marca 2021 ukazała się wersja na Xbox One.', '/22.04/galeria/5logo.jpg', '2015-09-15', '2022-05-02');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oceny`
--

CREATE TABLE `oceny` (
  `id` int(11) NOT NULL,
  `id_gry` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `ocena` int(5) NOT NULL,
  `opis` text NOT NULL,
  `data` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `oceny`
--

INSERT INTO `oceny` (`id`, `id_gry`, `id_user`, `ocena`, `opis`, `data`) VALUES
(5, 1, 7, 8, 'What is Lorem Ipsum?\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry.', '2022-04-30'),
(15, 1, 1, 9, 'saasfas', '2022-05-01');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `mail` varchar(60) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `register_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `mail`, `role`, `register_date`) VALUES
(1, 'SADmin', 'zaq1@WSX', 'SADmin@ratedex.com', 'admin', '2022-04-26'),
(5, 'user', 'user', 'mamapiekiego@zupa.pl', 'user', '2022-04-26'),
(7, 'qwewqe', 'novell', 'stephanebernet@awspe.ga', 'user', '2022-04-29'),
(9, 'hejtersus', 'jebac', 'pieprzsie@sos.pl', 'user', '2022-05-01');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `gry`
--
ALTER TABLE `gry`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `oceny`
--
ALTER TABLE `oceny`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_gry` (`id_gry`) USING BTREE,
  ADD KEY `id_user` (`id_user`) USING BTREE;

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `gry`
--
ALTER TABLE `gry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `oceny`
--
ALTER TABLE `oceny`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `oceny`
--
ALTER TABLE `oceny`
  ADD CONSTRAINT `id_gry` FOREIGN KEY (`id_gry`) REFERENCES `gry` (`id`),
  ADD CONSTRAINT `id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
