-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Εξυπηρετητής: 127.0.0.1
-- Χρόνος δημιουργίας: 01 Φεβ 2026 στις 10:52:34
-- Έκδοση διακομιστή: 10.4.32-MariaDB
-- Έκδοση PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `diplwmatikh_erg2`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `akyrwsh_diplwmatikhs`
--

CREATE TABLE `akyrwsh_diplwmatikhs` (
  `akyrwsh_id` int(11) NOT NULL,
  `diplwmatikh_id` int(11) DEFAULT NULL,
  `arithmos_gen_synel` varchar(50) DEFAULT NULL,
  `hmeromhnia_gen_synel` int(11) DEFAULT NULL,
  `logos_akyrwshs` text DEFAULT NULL,
  `hmeromhnia_akyrwshs` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `anakoinwseis`
--

CREATE TABLE `anakoinwseis` (
  `anakoinwsh_id` int(11) NOT NULL,
  `diplwmatikh_id` int(11) NOT NULL,
  `hmeromhnia_parousiashs` datetime NOT NULL,
  `thema` varchar(255) NOT NULL,
  `perigrafh` text NOT NULL,
  `dhmiourgithike` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `diplwmatikh`
--

CREATE TABLE `diplwmatikh` (
  `diplwmatikh_id` int(11) NOT NULL,
  `thema` varchar(255) NOT NULL,
  `perigrafh` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `mathites_id` int(11) DEFAULT NULL,
  `epivlepwn_id` int(11) DEFAULT NULL,
  `katastash` enum('Μη Ανατεθειμένη','Υπό Ανάθεση','Προσωρινή Κατοχύρωση','Ενεργή','Υπό εξέταση','Ολοκληρωμένη','Ακυρωμένη') DEFAULT 'Μη Ανατεθειμένη',
  `dhmiourgithike` timestamp NOT NULL DEFAULT current_timestamp(),
  `anatethike` timestamp NULL DEFAULT NULL,
  `oloklirwthike` timestamp NULL DEFAULT NULL,
  `hmera_wra_parousiashs` timestamp NOT NULL DEFAULT current_timestamp(),
  `typos_parousiashs` enum('Διαδικτυακά','Διά Ζώσης') DEFAULT NULL,
  `topothesia_parousiashs` varchar(255) DEFAULT NULL,
  `proxeiro_arxeio` varchar(255) DEFAULT NULL,
  `epitrepetai_anakoinwsh` tinyint(1) DEFAULT 0,
  `energopoihsh_eisodou_vathmou` tinyint(1) DEFAULT 0,
  `nimertis_url` varchar(500) DEFAULT NULL,
  `nimertis_submitted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `diplwmatikh`
--

INSERT INTO `diplwmatikh` (`diplwmatikh_id`, `thema`, `perigrafh`, `link`, `mathites_id`, `epivlepwn_id`, `katastash`, `dhmiourgithike`, `anatethike`, `oloklirwthike`, `hmera_wra_parousiashs`, `typos_parousiashs`, `topothesia_parousiashs`, `proxeiro_arxeio`, `epitrepetai_anakoinwsh`, `energopoihsh_eisodou_vathmou`, `nimertis_url`, `nimertis_submitted_at`) VALUES
(8, 'σρηφγβ', 'δβζψ ', '', NULL, 1, 'Μη Ανατεθειμένη', '2025-01-10 08:53:12', '2025-12-30 19:51:27', NULL, '2025-01-06 11:24:34', NULL, NULL, NULL, 0, 0, NULL, NULL),
(9, 'αδβζψω ', '&lt;σ&lt;ωχ', '', NULL, 1, 'Μη Ανατεθειμένη', '2025-01-10 08:52:36', '2025-12-18 23:55:44', NULL, '2025-01-06 11:24:47', NULL, NULL, NULL, 0, 0, NULL, NULL),
(10, 'σρηφγβ', 'δβζψ ', '', NULL, 1, 'Μη Ανατεθειμένη', '2025-01-06 11:28:02', NULL, NULL, '2025-01-06 11:28:02', NULL, NULL, NULL, 0, 0, NULL, NULL),
(19, 'fgbfc cvdfx', 'efdscxsfdscsd', '', NULL, 1, 'Μη Ανατεθειμένη', '2025-01-08 10:25:24', NULL, NULL, '2025-01-08 10:25:24', NULL, NULL, NULL, 0, 0, NULL, NULL),
(20, 'Διπλωματική 1', 'ργφγσφφ', '', 1, 1, 'Ενεργή', '2025-12-12 10:39:38', '2025-12-16 08:32:21', NULL, '2025-12-12 10:39:38', NULL, NULL, NULL, 0, 0, NULL, NULL),
(21, 'ΕΛΑ', 'ΞΓ', '', 3, 2, 'Υπό εξέταση', '2025-12-18 09:30:07', '2025-12-28 11:25:33', NULL, '2026-06-12 08:30:00', 'Διαδικτυακά', 'https://youtu.be/L-vFf2jxSK8?si=u1LGYyf_qYHKTG_9', 'draft_6952ecf2be03a6.93476594.pdf', 1, 1, NULL, NULL),
(27, 'Διπλωματική 2', 'Δύσκολη πολύ!!', 'https://youtu.be/vaW7PHKW02o?si=e0A-9WlA0f_Bz0W8', 2, 1, 'Υπό εξέταση', '2025-12-20 13:18:18', '2025-12-23 09:51:51', NULL, '2026-05-23 11:50:00', 'Διά Ζώσης', 'Α', 'draft_6951269ee3eb72.92784093.pdf', 1, 1, NULL, NULL),
(28, 'Τεχνητή', 'γψνβψν', '', 4, 2, 'Προσωρινή Κατοχύρωση', '2025-12-28 12:01:24', '2025-12-28 12:02:12', NULL, '2025-12-28 12:01:24', NULL, NULL, NULL, 0, 0, NULL, NULL),
(29, 'ΔΙΠΛΩΜΑΤΙΚΗ', 'Περιγραφή.', NULL, 7, 5, 'Υπό εξέταση', '2025-12-30 19:00:59', '2025-12-30 19:01:52', NULL, '2026-03-21 19:00:00', 'Διά Ζώσης', 'Αμφιθέατρο Γ\'', 'draft_69618020afe274.11591095.pdf', 1, 1, NULL, NULL);

--
-- Δείκτες `diplwmatikh`
--
DELIMITER $$
CREATE TRIGGER `akyrwsh_anatheshs_diplwmatikhs` AFTER UPDATE ON `diplwmatikh` FOR EACH ROW BEGIN 
    IF NEW.katastash = 'Ακυρωμένη' AND OLD.katastash != 'Ακυρωμένη' THEN 
        INSERT INTO akyrwsh_diplwmatikhs(diplwmatikh_id, arithmos_gen_synel, hmeromhnia_gen_synel, logos_akyrwshs)
        VALUES (NEW.diplwmatikh_id, NULL, NULL, 'Επιθυμία του μαθητή');
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `allagh_se_oloklhrwmenh` AFTER UPDATE ON `diplwmatikh` FOR EACH ROW BEGIN 
    DECLARE yparxei_vathmos INT DEFAULT 0;
    DECLARE yparxei_link INT DEFAULT 0;

    IF NEW.katastash = 'Ολοκληρωμένη' AND OLD.katastash != 'Ολοκληρωμένη' THEN 
        SELECT COUNT(*) INTO yparxei_vathmos 
        FROM vathmos
        WHERE diplwmatikh_id = NEW.diplwmatikh_id;

        SELECT COUNT(*) INTO yparxei_link 
        FROM syndesmos_diplwmatikhs
        WHERE diplwmatikh_id = NEW.diplwmatikh_id;

        IF yparxei_vathmos > 0 AND yparxei_link > 0 THEN 
            UPDATE diplwmatikh 
            SET katastash = 'Ολοκληρωμένη'
            WHERE diplwmatikh_id = NEW.diplwmatikh_id;
        ELSE
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Οι προϋποθέσεις για να αλλάξει σε "Ολοκληρωμένη" δεν επαληθεύονται.'; 
        END IF;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `ananewsh_katastashs_foithth` AFTER UPDATE ON `diplwmatikh` FOR EACH ROW BEGIN  
	IF (NEW.katastash IN ('Ανατεθημένη', 'Ενεργή', 'Υπό Εξέταση', 'Ολοκληρωμένη', 'Ακυρωμένη'))  
THEN  
		UPDATE mathites  
        SET katastash = NEW.katastash 
        WHERE mathites_id = NEW.mathites_id;
	END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `anathesi_monadikhs_diplwmatikhs` BEFORE UPDATE ON `diplwmatikh` FOR EACH ROW BEGIN  
	DECLARE diplh_anathesi INT;
    IF (NEW.mathites_id IS NOT NULL AND OLD.mathites_id IS NULL) THEN
    
		SELECT COUNT(*)
        INTO diplh_anathesi
        FROM diplwmatikh
        WHERE diplwmatikh_id = NEW.diplwmatikh_id AND mathites_id IS NOT NULL;
        
        IF diplh_anathesi > 0 THEN  
			SIGNAL SQLSTATE '45000'  
            SET MESSAGE_TEXT = 'Η διπλωματική έχει ήδη ανατεθεί σε άλλον φοιτητή.' ;  
		END IF;  
	END IF;  
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `apotroph_allagwn_meta_thn_oloklhrwsh` BEFORE UPDATE ON `diplwmatikh` FOR EACH ROW BEGIN 
    IF old.katastash = 'Ολοκληρωμένη' THEN 
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Δεν επιτρέπονται αλλαγές όταν η κατάσταση είναι "Ολοκληρωμένη".' ; 
    END IF;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `eisodos_sto_praktiko` AFTER UPDATE ON `diplwmatikh` FOR EACH ROW BEGIN  
    IF NEW.katastash = 'Ενεργή' AND OLD.katastash != 'Ενεργή' THEN 
        INSERT INTO praktiko_diplwmatikwn (diplwmatikh_id, arithmos_praktikou, hmeromhnia_praktikou)
        VALUES (NEW.diplwmatikh_id, NULL, NULL);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `epikyrwsh_allaghs_katastashs_diplwmatikhs` BEFORE UPDATE ON `diplwmatikh` FOR EACH ROW BEGIN
    -- Έλεγχος ΜΟΝΟ αν αλλάζει η κατάσταση
    IF OLD.katastash <> NEW.katastash THEN

        /* Από Μη Ανατεθειμένη */
        IF OLD.katastash = 'Μη Ανατεθειμένη' THEN
            IF NEW.katastash NOT IN ('Υπό Ανάθεση', 'Προσωρινή Κατοχύρωση') THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT =
                'Μη επιτρεπτή μετάβαση από Μη Ανατεθειμένη.';
            END IF;
        END IF;

    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `epitrepetai_anakoinwsh_leptomereiwn_parousiashs` BEFORE UPDATE ON `diplwmatikh` FOR EACH ROW BEGIN  
    IF (NEW.katastash= 'Υπό Εξέταση' AND 
        NEW.hmera_wra_parousiashs IS NOT NULL AND 
        NEW.typos_parousiashs IS NOT NULL AND 
        NEW.topothesia_parousiashs IS NOT NULL) THEN 

        SET NEW.epitrepetai_anakoinwsh = 1;
    ELSE
        SET NEW.epitrepetai_anakoinwsh = 0;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `eksetash_diplwmatikhs`
--

CREATE TABLE `eksetash_diplwmatikhs` (
  `eksetash_id` int(11) NOT NULL,
  `diplwmatikh_id` int(11) DEFAULT NULL,
  `mathitis_id` int(11) DEFAULT NULL,
  `hmeromhnia_eksetashs` datetime DEFAULT NULL,
  `typos_eksetashs` enum('Διά ζώσης','Διαδικτυακά') NOT NULL,
  `meros` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `grammateia`
--

CREATE TABLE `grammateia` (
  `grammateia_id` int(11) NOT NULL,
  `xrhstes_id` int(11) NOT NULL,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `katastaseis_istoriko`
--

CREATE TABLE `katastaseis_istoriko` (
  `istoriko_id` int(11) NOT NULL,
  `diplwmatikh_id` int(11) NOT NULL,
  `katastash` enum('Υπό ανάθεση','Ενεργή','Υπό εξέταση','Ολοκληρωμένη','Ακυρωμένη','Προσωρινή Κατοχύρωση') NOT NULL,
  `allagh_ap` varchar(255) DEFAULT NULL,
  `allagh_se` varchar(255) DEFAULT NULL,
  `allagh_hmeromhnia` timestamp NOT NULL DEFAULT current_timestamp(),
  `kathigiths_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `katastaseis_istoriko`
--

INSERT INTO `katastaseis_istoriko` (`istoriko_id`, `diplwmatikh_id`, `katastash`, `allagh_ap`, `allagh_se`, `allagh_hmeromhnia`, `kathigiths_id`) VALUES
(1, 8, 'Προσωρινή Κατοχύρωση', 'Υπό ανάθεση', 'Προσωρινή Κατοχύρωση', '2025-01-10 08:52:25', 1),
(2, 9, 'Προσωρινή Κατοχύρωση', 'Υπό ανάθεση', 'Προσωρινή Κατοχύρωση', '2025-01-10 08:52:36', 1),
(3, 8, 'Προσωρινή Κατοχύρωση', 'Υπό ανάθεση', 'Προσωρινή Κατοχύρωση', '2025-01-10 08:53:12', 1);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `kathigites`
--

CREATE TABLE `kathigites` (
  `kathigites_id` int(11) NOT NULL,
  `xrhstes_id` int(11) NOT NULL,
  `thlefwno` varchar(15) DEFAULT NULL,
  `rolos` enum('Epivlepwn','Trimelisepitropi') DEFAULT 'Trimelisepitropi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `kathigites`
--

INSERT INTO `kathigites` (`kathigites_id`, `xrhstes_id`, `thlefwno`, `rolos`) VALUES
(1, 1, '2610123456', 'Epivlepwn'),
(2, 2, '2610000002', 'Epivlepwn'),
(3, 3, '2610123457', 'Trimelisepitropi'),
(4, 4, '2610123458', 'Trimelisepitropi'),
(5, 16, '2610123459', 'Trimelisepitropi'),
(6, 17, '2610123460', 'Trimelisepitropi'),
(7, 18, '2610123461', 'Trimelisepitropi'),
(8, 19, '2610123462', 'Trimelisepitropi');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `mathites`
--

CREATE TABLE `mathites` (
  `mathites_id` int(11) NOT NULL,
  `xrhstes_id` int(11) NOT NULL,
  `thlefwno` char(10) NOT NULL,
  `am` varchar(50) NOT NULL,
  `katastash` enum('Υπό ανάθεση','Ενεργή','Υπό εξέταση','Ολοκληρωμένη','Ακυρωμένη') DEFAULT 'Υπό ανάθεση',
  `diefthinsi` varchar(255) DEFAULT NULL,
  `stathero` char(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `mathites`
--

INSERT INTO `mathites` (`mathites_id`, `xrhstes_id`, `thlefwno`, `am`, `katastash`, `diefthinsi`, `stathero`) VALUES
(1, 5, '6987313286', '12345678', 'Ενεργή', 'Σαραβάλι', '2610322073'),
(2, 6, '6940000002', '10519002', 'Υπό εξέταση', 'Οδός Ερμού 90', '2610222222'),
(3, 7, '6940000003', '10519003', 'Υπό εξέταση', 'Οδός Νίκης 3', '2610333333'),
(4, 8, '6940000004', '10519004', 'Υπό ανάθεση', 'Οδός Κύπρου 4', '2610444444'),
(5, 9, '6940000005', '10519005', 'Υπό ανάθεση', 'Οδός Κορίνθου 5', '2610555555'),
(6, 10, '6940000006', '10519006', 'Υπό ανάθεση', 'Οδός Ρήγα Φεραίου 6', '2610666666'),
(7, 11, '6940000007', '10519007', 'Υπό εξέταση', 'Οδός Μαιζώνος 7', '2610777777'),
(8, 12, '6940000008', '10519008', 'Υπό ανάθεση', 'Οδός Κανακάρη 8', '2610888888'),
(9, 13, '6940000009', '10519009', 'Υπό ανάθεση', 'Οδός Αγίου Ανδρέου 9', '2610999999'),
(10, 14, '6940000010', '10519010', 'Υπό ανάθεση', 'Οδός Πανεπιστημίου 10', '2610000000');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `praktiko_diplwmatikwn`
--

CREATE TABLE `praktiko_diplwmatikwn` (
  `praktiko_id` int(11) NOT NULL,
  `diplwmatikh_id` int(11) DEFAULT NULL,
  `arithmos_praktikou` varchar(50) DEFAULT NULL,
  `hmeromhnia_praktikou` date DEFAULT NULL,
  `dhmiourgithike` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `praktiko_diplwmatikwn`
--

INSERT INTO `praktiko_diplwmatikwn` (`praktiko_id`, `diplwmatikh_id`, `arithmos_praktikou`, `hmeromhnia_praktikou`, `dhmiourgithike`) VALUES
(1, 20, NULL, NULL, '2025-12-16 08:35:27'),
(2, 27, NULL, NULL, '2025-12-23 09:54:06'),
(3, 21, NULL, NULL, '2025-12-28 11:37:26'),
(4, 29, NULL, NULL, '2025-12-30 19:03:52');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `prosklhsh`
--

CREATE TABLE `prosklhsh` (
  `prosklhsh_id` int(11) NOT NULL,
  `diplwmatikh_id` int(11) DEFAULT NULL,
  `kathigitis_id` int(11) DEFAULT NULL,
  `hmeromhnia_prosklhshs` timestamp NOT NULL DEFAULT current_timestamp(),
  `apanthsh` enum('Δεκτή','Απορρίπτεται') DEFAULT NULL,
  `hmeromhnia_apanthshs` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `prosklhsh`
--

INSERT INTO `prosklhsh` (`prosklhsh_id`, `diplwmatikh_id`, `kathigitis_id`, `hmeromhnia_prosklhshs`, `apanthsh`, `hmeromhnia_apanthshs`) VALUES
(16, 20, 5, '2025-12-16 08:33:26', 'Δεκτή', '2025-12-16 08:34:16'),
(17, 20, 2, '2025-12-16 08:33:44', 'Δεκτή', '2025-12-16 08:35:27'),
(22, 27, 6, '2025-12-23 09:52:32', 'Δεκτή', '2025-12-23 09:52:59'),
(23, 27, 3, '2025-12-23 09:53:41', 'Δεκτή', '2025-12-23 09:54:06'),
(24, 21, 1, '2025-12-28 11:34:11', 'Δεκτή', '2025-12-28 11:37:10'),
(25, 21, 6, '2025-12-28 11:36:42', 'Δεκτή', '2025-12-28 11:37:26'),
(26, 28, 8, '2025-12-28 12:24:05', 'Απορρίπτεται', '2025-12-30 19:03:48'),
(27, 28, 3, '2025-12-28 12:40:12', NULL, NULL),
(28, 29, 8, '2025-12-30 19:02:48', 'Δεκτή', '2025-12-30 19:03:52'),
(29, 29, 7, '2025-12-30 19:02:55', 'Δεκτή', '2025-12-30 19:03:12');

--
-- Δείκτες `prosklhsh`
--
DELIMITER $$
CREATE TRIGGER `enhmerwsh_katastashs_diplwmatikhs_se_energh` AFTER UPDATE ON `prosklhsh` FOR EACH ROW BEGIN
    DECLARE arithmos_apodektwn INT;

    -- Εισαγωγή στην τριμελή όταν κάποιος καθηγητής αποδεχτεί
    IF NEW.apanthsh = 'Δεκτή' THEN
        INSERT INTO trimelhs_epitroph (
            kathigiths_id, 
            diplwmatikh_id,
            prosklhsh_hmeromhnia,
            apodoxh_hmeromhnia
        )
        VALUES (
            NEW.kathigitis_id,
            NEW.diplwmatikh_id,
            NEW.hmeromhnia_prosklhshs,
            NOW()
        );
    END IF;

    -- Πόσες αποδοχές έχει η διπλωματική συνολικά
    SELECT COUNT(*)
    INTO arithmos_apodektwn
    FROM prosklhsh
    WHERE diplwmatikh_id = NEW.diplwmatikh_id
      AND apanthsh = 'Δεκτή';

    -- Όταν φτάσει 2 -> ενεργοποίηση διπλωματικής
    IF arithmos_apodektwn >= 2 THEN
        UPDATE diplwmatikh
        SET katastash = 'Ενεργή'
        WHERE diplwmatikh_id = NEW.diplwmatikh_id;
    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `prosklhsh_diplwmatikh`
--

CREATE TABLE `prosklhsh_diplwmatikh` (
  `prosklhsh_diplwmatikh_id` int(11) NOT NULL,
  `diplwmatikh_id` int(11) DEFAULT NULL,
  `kathigitis_id` int(11) DEFAULT NULL,
  `katastash` enum('Πρόσκληση αποδεκτή','Πρόσκληση εκρεμεί','Πρόσκληση Απορρίπτεται') DEFAULT 'Πρόσκληση εκρεμεί'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `shmeiwseis`
--

CREATE TABLE `shmeiwseis` (
  `shmeiwsh_id` int(11) NOT NULL,
  `diplwmatikh_id` int(11) NOT NULL,
  `kathigiths_id` int(11) NOT NULL,
  `keimeno` text NOT NULL,
  `hmeromhnia` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `sxolia`
--

CREATE TABLE `sxolia` (
  `sxolia_id` int(11) NOT NULL,
  `diplwmatikh_id` int(11) DEFAULT NULL,
  `kathigitis_id` int(11) DEFAULT NULL,
  `mathitis_id` int(11) DEFAULT NULL,
  `sxolio` text DEFAULT NULL,
  `dhmiourgithike` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `syndesmos_diplwmatikhs`
--

CREATE TABLE `syndesmos_diplwmatikhs` (
  `syndesmos_id` int(11) NOT NULL,
  `diplwmatikh_id` int(11) DEFAULT NULL,
  `apothiki_link` varchar(255) DEFAULT NULL,
  `dhmiourgithike` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `trimelhs_epitroph`
--

CREATE TABLE `trimelhs_epitroph` (
  `kathigiths_id` int(11) NOT NULL,
  `diplwmatikh_id` int(11) NOT NULL,
  `rolos` enum('Τριμελής Επιτροπή') DEFAULT 'Τριμελής Επιτροπή',
  `prosklhsh_hmeromhnia` timestamp NULL DEFAULT NULL,
  `apodoxh_hmeromhnia` timestamp NULL DEFAULT NULL,
  `aporripsh_hmeromhnia` timestamp NULL DEFAULT NULL,
  `katastash` enum('Ενεργή','Αποδεκτή','Απορριφθείσα') DEFAULT 'Ενεργή',
  `hmeromhnia_apodoxhs` timestamp NULL DEFAULT NULL,
  `hmeromhnia_aporripshs` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `trimelhs_epitroph`
--

INSERT INTO `trimelhs_epitroph` (`kathigiths_id`, `diplwmatikh_id`, `rolos`, `prosklhsh_hmeromhnia`, `apodoxh_hmeromhnia`, `aporripsh_hmeromhnia`, `katastash`, `hmeromhnia_apodoxhs`, `hmeromhnia_aporripshs`) VALUES
(1, 20, 'Τριμελής Επιτροπή', NULL, NULL, NULL, 'Ενεργή', NULL, NULL),
(1, 21, 'Τριμελής Επιτροπή', '2025-12-28 11:34:11', '2025-12-28 11:37:10', NULL, 'Ενεργή', NULL, NULL),
(1, 27, 'Τριμελής Επιτροπή', NULL, NULL, NULL, 'Ενεργή', NULL, NULL),
(2, 20, 'Τριμελής Επιτροπή', '2025-12-16 08:33:44', '2025-12-16 08:35:27', NULL, 'Ενεργή', NULL, NULL),
(2, 21, 'Τριμελής Επιτροπή', NULL, NULL, NULL, 'Ενεργή', NULL, NULL),
(3, 27, 'Τριμελής Επιτροπή', '2025-12-23 09:53:41', '2025-12-23 09:54:06', NULL, 'Ενεργή', NULL, NULL),
(5, 20, 'Τριμελής Επιτροπή', '2025-12-16 08:33:26', '2025-12-16 08:34:16', NULL, 'Ενεργή', NULL, NULL),
(5, 29, 'Τριμελής Επιτροπή', NULL, NULL, NULL, 'Ενεργή', NULL, NULL),
(6, 21, 'Τριμελής Επιτροπή', '2025-12-28 11:36:42', '2025-12-28 11:37:26', NULL, 'Ενεργή', NULL, NULL),
(6, 27, 'Τριμελής Επιτροπή', '2025-12-23 09:52:32', '2025-12-23 09:52:59', NULL, 'Ενεργή', NULL, NULL),
(7, 29, 'Τριμελής Επιτροπή', '2025-12-30 19:02:55', '2025-12-30 19:03:12', NULL, 'Ενεργή', NULL, NULL),
(8, 29, 'Τριμελής Επιτροπή', '2025-12-30 19:02:48', '2025-12-30 19:03:52', NULL, 'Ενεργή', NULL, NULL);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `vathmos`
--

CREATE TABLE `vathmos` (
  `vathmos_id` int(11) NOT NULL,
  `diplwmatikh_id` int(11) NOT NULL,
  `kathigitis_id` int(11) NOT NULL,
  `poiotita` decimal(4,2) NOT NULL,
  `xronos` decimal(4,2) NOT NULL,
  `keimeno` decimal(4,2) NOT NULL,
  `parousiasi` decimal(4,2) NOT NULL,
  `telikos_vathmos` decimal(4,2) NOT NULL,
  `hmeromhnia` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `vathmos`
--

INSERT INTO `vathmos` (`vathmos_id`, `diplwmatikh_id`, `kathigitis_id`, `poiotita`, `xronos`, `keimeno`, `parousiasi`, `telikos_vathmos`, `hmeromhnia`) VALUES
(1, 27, 1, 8.00, 6.50, 8.00, 7.00, 7.68, '2025-12-30 09:24:09'),
(2, 29, 5, 6.00, 7.00, 8.00, 6.00, 6.45, '2025-12-30 19:07:10'),
(3, 29, 7, 7.00, 8.00, 8.00, 7.00, 7.30, '2025-12-30 19:07:57'),
(4, 29, 8, 7.00, 7.50, 8.00, 8.00, 7.33, '2026-01-02 11:20:28');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `xrhstes`
--

CREATE TABLE `xrhstes` (
  `xrhstes_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `kwdikos` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rolos` enum('Didaskwn','Foititis','Grammateia') NOT NULL,
  `Plhres_onoma` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `xrhstes`
--

INSERT INTO `xrhstes` (`xrhstes_id`, `username`, `kwdikos`, `email`, `rolos`, `Plhres_onoma`, `created_at`) VALUES
(1, 'lecturer1', 'password1', 'lecturer1@upatras.gr', 'Didaskwn', 'Kathigitis 1', '2024-12-07 07:55:47'),
(2, 'lecturer2', 'password2', 'lecturer2@upatras.gr', 'Didaskwn', 'Lecturer 2', '2024-12-07 07:56:45'),
(3, 'lecturer3', 'password3', 'lecturer3@upatras.gr', 'Didaskwn', 'Lecturer 3', '2024-12-07 07:57:59'),
(4, 'lecturer4', 'password4', 'lecturer4@gmail.com', 'Didaskwn', 'Lecturer 4', '2024-12-07 07:58:53'),
(5, 'student 1', 'password 5', 'student1@upatras.gr', 'Foititis', 'Student 1', '2024-12-07 08:00:06'),
(6, 'student 2', 'password6', 'student2@upatras.gr', 'Foititis', 'Mathitis 2', '2024-12-07 08:02:34'),
(7, 'student 3', 'password7', 'student3@upatras.gr', 'Foititis', 'Student 3', '2024-12-07 08:04:11'),
(8, 'student 4', 'password8', 'student4@upatras.gr', 'Foititis', 'Student 4', '2024-12-07 08:05:18'),
(9, 'student 5', 'password9', 'student5@upatras.gr', 'Foititis', 'Student 5', '2024-12-07 08:06:02'),
(10, 'student 6', 'password10', 'student6@gmail.com', 'Foititis', 'Student 6', '2024-12-07 08:06:48'),
(11, 'student7', 'password11', 'student7@upatras.gr', 'Foititis', 'Student 7', '2024-12-07 08:07:46'),
(12, 'student 8', 'password12', 'student8@upatras.gr', 'Foititis', 'Student 8', '2024-12-07 08:08:43'),
(13, 'student9', 'password13', 'student9@upatras.gr', 'Foititis', 'Student 9', '2024-12-07 08:13:42'),
(14, 'student 10', 'password14', 'student10@upatras.gr', 'Foititis', 'Student 10', '2024-12-07 08:14:31'),
(15, 'secretary1', 'password15', 'secretary1@upatras.gr', 'Grammateia', 'Secretary 1', '2024-12-07 08:17:52'),
(16, 'lecturer5', 'password5', 'lecturer5@upatras.gr', 'Didaskwn', 'Kathigitis 5', '2025-12-10 08:00:00'),
(17, 'lecturer6', 'password6', 'lecturer6@upatras.gr', 'Didaskwn', 'Kathigitis 6', '2025-12-10 08:00:00'),
(18, 'lecturer7', 'password7', 'lecturer7@upatras.gr', 'Didaskwn', 'Kathigitis 7', '2025-12-10 08:00:00'),
(19, 'lecturer8', 'password8', 'lecturer8@upatras.gr', 'Didaskwn', 'Kathigitis 8', '2025-12-10 08:00:00'),
(35, 'student20', 'elaaa', '[value-4]', 'Foititis', '[value-6]', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `xronos_eksetashs`
--

CREATE TABLE `xronos_eksetashs` (
  `eksetash_id` int(11) NOT NULL,
  `diplwmatikh_id` int(11) DEFAULT NULL,
  `lepta` text DEFAULT NULL,
  `dhmiourgithike` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `akyrwsh_diplwmatikhs`
--
ALTER TABLE `akyrwsh_diplwmatikhs`
  ADD PRIMARY KEY (`akyrwsh_id`),
  ADD KEY `diplwmatikh_id` (`diplwmatikh_id`);

--
-- Ευρετήρια για πίνακα `anakoinwseis`
--
ALTER TABLE `anakoinwseis`
  ADD PRIMARY KEY (`anakoinwsh_id`),
  ADD KEY `diplwmatikh_id` (`diplwmatikh_id`);

--
-- Ευρετήρια για πίνακα `diplwmatikh`
--
ALTER TABLE `diplwmatikh`
  ADD PRIMARY KEY (`diplwmatikh_id`),
  ADD KEY `epivlepwn_id` (`epivlepwn_id`),
  ADD KEY `mathites_id` (`mathites_id`);

--
-- Ευρετήρια για πίνακα `eksetash_diplwmatikhs`
--
ALTER TABLE `eksetash_diplwmatikhs`
  ADD PRIMARY KEY (`eksetash_id`),
  ADD KEY `diplwmatikh_id` (`diplwmatikh_id`),
  ADD KEY `mathitis_id` (`mathitis_id`);

--
-- Ευρετήρια για πίνακα `grammateia`
--
ALTER TABLE `grammateia`
  ADD PRIMARY KEY (`grammateia_id`),
  ADD KEY `xrhstes_id` (`xrhstes_id`);

--
-- Ευρετήρια για πίνακα `katastaseis_istoriko`
--
ALTER TABLE `katastaseis_istoriko`
  ADD PRIMARY KEY (`istoriko_id`),
  ADD KEY `diplwmatikh_id` (`diplwmatikh_id`),
  ADD KEY `kathigiths_id` (`kathigiths_id`);

--
-- Ευρετήρια για πίνακα `kathigites`
--
ALTER TABLE `kathigites`
  ADD PRIMARY KEY (`kathigites_id`),
  ADD KEY `xrhstes_id` (`xrhstes_id`);

--
-- Ευρετήρια για πίνακα `mathites`
--
ALTER TABLE `mathites`
  ADD PRIMARY KEY (`mathites_id`),
  ADD UNIQUE KEY `am` (`am`),
  ADD KEY `xrhstes_id` (`xrhstes_id`);

--
-- Ευρετήρια για πίνακα `praktiko_diplwmatikwn`
--
ALTER TABLE `praktiko_diplwmatikwn`
  ADD PRIMARY KEY (`praktiko_id`),
  ADD KEY `diplwmatikh_id` (`diplwmatikh_id`);

--
-- Ευρετήρια για πίνακα `prosklhsh`
--
ALTER TABLE `prosklhsh`
  ADD PRIMARY KEY (`prosklhsh_id`),
  ADD KEY `diplwmatikh_id` (`diplwmatikh_id`),
  ADD KEY `kathigitis_id` (`kathigitis_id`);

--
-- Ευρετήρια για πίνακα `prosklhsh_diplwmatikh`
--
ALTER TABLE `prosklhsh_diplwmatikh`
  ADD PRIMARY KEY (`prosklhsh_diplwmatikh_id`),
  ADD KEY `diplwmatikh_id` (`diplwmatikh_id`),
  ADD KEY `kathigitis_id` (`kathigitis_id`);

--
-- Ευρετήρια για πίνακα `shmeiwseis`
--
ALTER TABLE `shmeiwseis`
  ADD PRIMARY KEY (`shmeiwsh_id`),
  ADD KEY `diplwmatikh_id` (`diplwmatikh_id`),
  ADD KEY `kathigiths_id` (`kathigiths_id`);

--
-- Ευρετήρια για πίνακα `sxolia`
--
ALTER TABLE `sxolia`
  ADD PRIMARY KEY (`sxolia_id`),
  ADD KEY `diplwmatikh_id` (`diplwmatikh_id`),
  ADD KEY `kathigitis_id` (`kathigitis_id`),
  ADD KEY `mathitis_id` (`mathitis_id`);

--
-- Ευρετήρια για πίνακα `syndesmos_diplwmatikhs`
--
ALTER TABLE `syndesmos_diplwmatikhs`
  ADD PRIMARY KEY (`syndesmos_id`),
  ADD KEY `diplwmatikh_id` (`diplwmatikh_id`);

--
-- Ευρετήρια για πίνακα `trimelhs_epitroph`
--
ALTER TABLE `trimelhs_epitroph`
  ADD PRIMARY KEY (`kathigiths_id`,`diplwmatikh_id`);

--
-- Ευρετήρια για πίνακα `vathmos`
--
ALTER TABLE `vathmos`
  ADD PRIMARY KEY (`vathmos_id`),
  ADD UNIQUE KEY `diplwmatikh_id` (`diplwmatikh_id`,`kathigitis_id`);

--
-- Ευρετήρια για πίνακα `xrhstes`
--
ALTER TABLE `xrhstes`
  ADD PRIMARY KEY (`xrhstes_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Ευρετήρια για πίνακα `xronos_eksetashs`
--
ALTER TABLE `xronos_eksetashs`
  ADD PRIMARY KEY (`eksetash_id`),
  ADD KEY `diplwmatikh_id` (`diplwmatikh_id`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `akyrwsh_diplwmatikhs`
--
ALTER TABLE `akyrwsh_diplwmatikhs`
  MODIFY `akyrwsh_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT για πίνακα `anakoinwseis`
--
ALTER TABLE `anakoinwseis`
  MODIFY `anakoinwsh_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT για πίνακα `diplwmatikh`
--
ALTER TABLE `diplwmatikh`
  MODIFY `diplwmatikh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT για πίνακα `eksetash_diplwmatikhs`
--
ALTER TABLE `eksetash_diplwmatikhs`
  MODIFY `eksetash_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT για πίνακα `grammateia`
--
ALTER TABLE `grammateia`
  MODIFY `grammateia_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT για πίνακα `katastaseis_istoriko`
--
ALTER TABLE `katastaseis_istoriko`
  MODIFY `istoriko_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT για πίνακα `kathigites`
--
ALTER TABLE `kathigites`
  MODIFY `kathigites_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT για πίνακα `mathites`
--
ALTER TABLE `mathites`
  MODIFY `mathites_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT για πίνακα `praktiko_diplwmatikwn`
--
ALTER TABLE `praktiko_diplwmatikwn`
  MODIFY `praktiko_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT για πίνακα `prosklhsh`
--
ALTER TABLE `prosklhsh`
  MODIFY `prosklhsh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT για πίνακα `prosklhsh_diplwmatikh`
--
ALTER TABLE `prosklhsh_diplwmatikh`
  MODIFY `prosklhsh_diplwmatikh_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT για πίνακα `shmeiwseis`
--
ALTER TABLE `shmeiwseis`
  MODIFY `shmeiwsh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT για πίνακα `sxolia`
--
ALTER TABLE `sxolia`
  MODIFY `sxolia_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT για πίνακα `syndesmos_diplwmatikhs`
--
ALTER TABLE `syndesmos_diplwmatikhs`
  MODIFY `syndesmos_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT για πίνακα `vathmos`
--
ALTER TABLE `vathmos`
  MODIFY `vathmos_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT για πίνακα `xrhstes`
--
ALTER TABLE `xrhstes`
  MODIFY `xrhstes_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT για πίνακα `xronos_eksetashs`
--
ALTER TABLE `xronos_eksetashs`
  MODIFY `eksetash_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `akyrwsh_diplwmatikhs`
--
ALTER TABLE `akyrwsh_diplwmatikhs`
  ADD CONSTRAINT `akyrwsh_diplwmatikhs_ibfk_1` FOREIGN KEY (`diplwmatikh_id`) REFERENCES `diplwmatikh` (`diplwmatikh_id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `anakoinwseis`
--
ALTER TABLE `anakoinwseis`
  ADD CONSTRAINT `anakoinwseis_ibfk_1` FOREIGN KEY (`diplwmatikh_id`) REFERENCES `diplwmatikh` (`diplwmatikh_id`);

--
-- Περιορισμοί για πίνακα `diplwmatikh`
--
ALTER TABLE `diplwmatikh`
  ADD CONSTRAINT `diplwmatikh_ibfk_1` FOREIGN KEY (`epivlepwn_id`) REFERENCES `kathigites` (`kathigites_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `diplwmatikh_ibfk_2` FOREIGN KEY (`mathites_id`) REFERENCES `mathites` (`mathites_id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `eksetash_diplwmatikhs`
--
ALTER TABLE `eksetash_diplwmatikhs`
  ADD CONSTRAINT `eksetash_diplwmatikhs_ibfk_1` FOREIGN KEY (`diplwmatikh_id`) REFERENCES `diplwmatikh` (`diplwmatikh_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `eksetash_diplwmatikhs_ibfk_2` FOREIGN KEY (`mathitis_id`) REFERENCES `mathites` (`mathites_id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `grammateia`
--
ALTER TABLE `grammateia`
  ADD CONSTRAINT `grammateia_ibfk_1` FOREIGN KEY (`xrhstes_id`) REFERENCES `xrhstes` (`xrhstes_id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `katastaseis_istoriko`
--
ALTER TABLE `katastaseis_istoriko`
  ADD CONSTRAINT `katastaseis_istoriko_ibfk_1` FOREIGN KEY (`diplwmatikh_id`) REFERENCES `diplwmatikh` (`diplwmatikh_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `katastaseis_istoriko_ibfk_2` FOREIGN KEY (`kathigiths_id`) REFERENCES `kathigites` (`kathigites_id`) ON DELETE SET NULL;

--
-- Περιορισμοί για πίνακα `kathigites`
--
ALTER TABLE `kathigites`
  ADD CONSTRAINT `kathigites_ibfk_1` FOREIGN KEY (`xrhstes_id`) REFERENCES `xrhstes` (`xrhstes_id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `mathites`
--
ALTER TABLE `mathites`
  ADD CONSTRAINT `mathites_ibfk_1` FOREIGN KEY (`xrhstes_id`) REFERENCES `xrhstes` (`xrhstes_id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `praktiko_diplwmatikwn`
--
ALTER TABLE `praktiko_diplwmatikwn`
  ADD CONSTRAINT `praktiko_diplwmatikwn_ibfk_1` FOREIGN KEY (`diplwmatikh_id`) REFERENCES `diplwmatikh` (`diplwmatikh_id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `prosklhsh`
--
ALTER TABLE `prosklhsh`
  ADD CONSTRAINT `prosklhsh_ibfk_1` FOREIGN KEY (`diplwmatikh_id`) REFERENCES `diplwmatikh` (`diplwmatikh_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prosklhsh_ibfk_2` FOREIGN KEY (`kathigitis_id`) REFERENCES `kathigites` (`kathigites_id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `prosklhsh_diplwmatikh`
--
ALTER TABLE `prosklhsh_diplwmatikh`
  ADD CONSTRAINT `prosklhsh_diplwmatikh_ibfk_1` FOREIGN KEY (`diplwmatikh_id`) REFERENCES `diplwmatikh` (`diplwmatikh_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prosklhsh_diplwmatikh_ibfk_2` FOREIGN KEY (`kathigitis_id`) REFERENCES `kathigites` (`kathigites_id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `shmeiwseis`
--
ALTER TABLE `shmeiwseis`
  ADD CONSTRAINT `shmeiwseis_ibfk_1` FOREIGN KEY (`diplwmatikh_id`) REFERENCES `diplwmatikh` (`diplwmatikh_id`),
  ADD CONSTRAINT `shmeiwseis_ibfk_2` FOREIGN KEY (`kathigiths_id`) REFERENCES `kathigites` (`kathigites_id`);

--
-- Περιορισμοί για πίνακα `sxolia`
--
ALTER TABLE `sxolia`
  ADD CONSTRAINT `sxolia_ibfk_1` FOREIGN KEY (`diplwmatikh_id`) REFERENCES `diplwmatikh` (`diplwmatikh_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sxolia_ibfk_2` FOREIGN KEY (`kathigitis_id`) REFERENCES `kathigites` (`kathigites_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sxolia_ibfk_3` FOREIGN KEY (`mathitis_id`) REFERENCES `mathites` (`mathites_id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `syndesmos_diplwmatikhs`
--
ALTER TABLE `syndesmos_diplwmatikhs`
  ADD CONSTRAINT `syndesmos_diplwmatikhs_ibfk_1` FOREIGN KEY (`diplwmatikh_id`) REFERENCES `diplwmatikh` (`diplwmatikh_id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `xronos_eksetashs`
--
ALTER TABLE `xronos_eksetashs`
  ADD CONSTRAINT `xronos_eksetashs_ibfk_1` FOREIGN KEY (`diplwmatikh_id`) REFERENCES `diplwmatikh` (`diplwmatikh_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
