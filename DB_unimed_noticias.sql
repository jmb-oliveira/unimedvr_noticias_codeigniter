-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 
-- Versão do Servidor: 5.5.24-log
-- Versão do PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `unimed_noticias`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `unmd_categorias`
--

CREATE TABLE IF NOT EXISTS `unmd_categorias` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `dcategoria` varchar(60) NOT NULL,
  `visivel_desktop` tinyint(1) NOT NULL,
  `visivel_mobile` tinyint(1) NOT NULL,
  `id_autor` int(11) NOT NULL,
  `created_on` int(11) NOT NULL,
  `updated_on` int(11) DEFAULT NULL,
  `removed_on` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_categoria`),
  KEY `id_autor` (`id_autor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `unmd_logs`
--

CREATE TABLE IF NOT EXISTS `unmd_logs` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(255) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `created_on` int(11) NOT NULL,
  PRIMARY KEY (`id_log`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `unmd_noticias`
--

CREATE TABLE IF NOT EXISTS `unmd_noticias` (
  `id_noticia` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `texto` blob NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `visivel_desktop` tinyint(1) NOT NULL,
  `visivel_mobile` tinyint(1) NOT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `publicada_em` int(11) DEFAULT NULL,
  `id_autor` int(11) NOT NULL,
  `updated_on` int(11) DEFAULT NULL,
  `removed_on` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_noticia`),
  KEY `id_categoria` (`id_categoria`),
  KEY `id_autor` (`id_autor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `unmd_noticias_imagens`
--

CREATE TABLE IF NOT EXISTS `unmd_noticias_imagens` (
  `id_imagem` int(11) NOT NULL AUTO_INCREMENT,
  `ordem` int(11) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `id_noticia` int(11) NOT NULL,
  PRIMARY KEY (`id_imagem`),
  KEY `id_noticia` (`id_noticia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `unmd_usuarios`
--

CREATE TABLE IF NOT EXISTS `unmd_usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `acesso` tinyint(1) NOT NULL COMMENT '1- autor, 2-admin',
  `habilitado` tinyint(1) NOT NULL DEFAULT '1',
  `dusuario` varchar(60) NOT NULL,
  `login` varchar(60) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_on` int(11) NOT NULL,
  `updated_on` int(11) DEFAULT NULL,
  `removed_on` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `unmd_usuarios`
--

INSERT INTO `unmd_usuarios` (`id_usuario`, `acesso`, `habilitado`, `dusuario`, `login`, `senha`, `email`, `created_on`, `updated_on`, `removed_on`) VALUES
(1, 2, 1, 'Administrador', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'contato@email.com', 1437789655, NULL, NULL);

--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `unmd_categorias`
--
ALTER TABLE `unmd_categorias`
  ADD CONSTRAINT `unmd_categorias_ibfk_1` FOREIGN KEY (`id_autor`) REFERENCES `unmd_usuarios` (`id_usuario`);

--
-- Restrições para a tabela `unmd_logs`
--
ALTER TABLE `unmd_logs`
  ADD CONSTRAINT `unmd_logs_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `unmd_usuarios` (`id_usuario`);

--
-- Restrições para a tabela `unmd_noticias`
--
ALTER TABLE `unmd_noticias`
  ADD CONSTRAINT `unmd_noticias_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `unmd_categorias` (`id_categoria`),
  ADD CONSTRAINT `unmd_noticias_ibfk_2` FOREIGN KEY (`id_autor`) REFERENCES `unmd_usuarios` (`id_usuario`);

--
-- Restrições para a tabela `unmd_noticias_imagens`
--
ALTER TABLE `unmd_noticias_imagens`
  ADD CONSTRAINT `unmd_noticias_imagens_ibfk_1` FOREIGN KEY (`id_noticia`) REFERENCES `unmd_noticias` (`id_noticia`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
