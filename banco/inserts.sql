
-- =====================================================================
-- MedTime - Dados de exemplo (ETAPA 1)
-- =====================================================================
SET NAMES utf8mb4;
USE `banco`;

-- ---------------------------------------------------------------------
-- Pessoas (cuidadores e pacientes) -> IDs 1 a 15
-- ---------------------------------------------------------------------
INSERT INTO `pessoa`
(`pessoa_nome`, `pessoa_email`, `pessoa_senha`, `pessoa_tipo`, `pessoa_data_nascimento`, `pessoa_peso`, `pessoa_altura`)
VALUES
('Lucas Ferreira', 'lucas@email.com', '$2y$12$GNCUpEXvZQpI0daJxGIKAe/7vQqG9uDsjODW4dj2Kc9pQbXKMSQoK', 'cuidador', '1985-03-12', 78.0, 1.8),
('Beatriz Almeida', 'beatriz@email.com', '$2y$12$GNCUpEXvZQpI0daJxGIKAe/7vQqG9uDsjODW4dj2Kc9pQbXKMSQoK', 'paciente', '1949-06-25', 63.0, 1.62),
('Roberto Lima', 'roberto@email.com', '$2y$12$GNCUpEXvZQpI0daJxGIKAe/7vQqG9uDsjODW4djK2c9pQbXKMSQoK', 'paciente', '1952-09-14', 72.0, 1.7),
('Fernanda Rocha', 'fernanda@email.com', '$2y$12$GNCUpEXvZQpI0daJxGIKAe/7vQqG9uDsjODW4dj2Kc9pQbXKMSQoK', 'cuidador', '1990-01-08', 65.0, 1.67),
('Marcos Pereira', 'marcos@email.com', '$2y$12$GNCUpEXvZQpI0daJxGIKAe/7vQqG9uDsjODW4dj2Kc9pQbXKMSQoK', 'paciente', '1950-04-17', 70.0, 1.72),
('Juliana Martins', 'juliana@email.com', '$2y$12$GNCUpEXvZpQpI0daJxGIKAe/7vQqG9uDsjODW4dj2Kc9pQbXKMSQoK', 'cuidador', '1988-07-21', 60.0, 1.65),
('Antônio Ribeiro', 'antonio@email.com', '$2y$12$GNCUpEXvZpQpI0daJxGIKAe/7vQqG9uDsjODW4dj2Kc9pQbXKMSQoK', 'paciente', '1947-11-30', 67.0, 1.68),
('Camila Barbosa', 'camila@email.com', '$2y$12$GNCUpEXvZpQpI0daJxGIKAe/7vQqG9uDsjODW4dj2Kc9pQbXKMSQoK', 'cuidador', '1992-02-19', 58.0, 1.63),
('Helena Cardoso', 'helena@email.com', '$2y$12$GNCUpEXvZpQpI0daJxGIKAe/7vQqG9uDsjODW4dj2Kc9pQbXKMSQoK', 'paciente', '1954-05-09', 61.0, 1.59),
('Rafael Gomes', 'rafael@email.com', '$2y$12$GNCUpEXvZpQpI0daJxGIKAe/7vQqG9uDsjODW4dj2Kc9pQbXKMSQoK', 'cuidador', '1983-10-11', 82.0, 1.82),
('Sônia Mendes', 'sonia@email.com', '$2y$12$GNCUpEXvZpQpI0daJxGIKAe/7vQqG9uDsjODW4dj2Kc9pQbXKMSQoK', 'paciente', '1946-08-16', 64.0, 1.6),
('Paulo Carvalho', 'paulo@email.com', '$2y$12$GNCUpEXvZpQpI0daJxGIKAe/7vQqG9uDsjODW4dj2Kc9pQbXKMSQoK', 'cuidador', '1987-12-04', 76.0, 1.76),
('Teresa Nunes', 'teresa@email.com', '$2y$12$GNCUpEXvZpQpI0daJxGIKAe/7vQqG9uDsjODW4dj2Kc9pQbXKMSQoK', 'paciente', '1951-03-23', 59.0, 1.57),
('Gabriel Costa', 'gabriel@email.com', '$2y$12$GNCUpEXvZpQpI0daJxGIKAe/7vQqG9uDsjODW4dj2Kc9pQbXKMSQoK', 'cuidador', '1991-09-07', 74.0, 1.78),
('Lourdes Dias', 'lourdes@email.com', '$2y$12$GNCUpEXvZpQpI0daJxGIKAe/7vQqG9uDsjODW4dj2Kc9pQbXKMSQoK', 'paciente', '1945-01-27', 56.0, 1.54);

-- ---------------------------------------------------------------------
-- Cuidados -> IDs 1 a 8
-- ---------------------------------------------------------------------
INSERT INTO `cuidado`
(`cuidado_nome`, `cuidado_data_nas`, `cuidado_foto`, `fk_pessoa_cuidador`, `fk_pessoa_paciente`)
VALUES
('Cuidado Beatriz', '1949-06-25', '', 1, 2),
('Cuidado Roberto', '1952-09-14', '', 1, 3),
('Cuidado Marcos', '1950-04-17', '', 4, 5),
('Cuidado Antônio', '1947-11-30', '', 6, 7),
('Cuidado Helena', '1954-05-09', '', 8, 9),
('Cuidado Sônia', '1946-08-16', '', 10, 11),
('Cuidado Teresa', '1951-03-23', '', 12, 13),
('Cuidado Lourdes', '1945-01-27', '', 14, 15);

-- ---------------------------------------------------------------------
-- Medicamentos (37 registros)
-- medicamento_foto contém somente o nome do arquivo PNG.
-- ---------------------------------------------------------------------
INSERT INTO `medicamento`
(`medicamento_nome`, `medicamento_foto`, `medicamento_laboratorio`, `medicamento_categoria`, `medicamento_obervacao`)
VALUES
('Dipirona', 'dipirona.png', 'Genérico', 'Dor e febre', 'Analgésico e antitérmico. Utilizar conforme orientação profissional.'),
('Paracetamol', 'paracetamol.png', 'Genérico', 'Dor e febre', 'Analgésico e antitérmico. Utilizar conforme orientação profissional.'),
('Ácido acetilsalicílico', 'acido-acetilsalicilico.png', 'Genérico', 'Dor e febre', 'Medicamento com ação analgésica e anti-inflamatória.'),
('Ibuprofeno', 'ibuprofeno.png', 'Genérico', 'Dor e inflamação', 'Anti-inflamatório não esteroidal.'),
('Aceclofenaco', 'aceclofenaco.png', 'Genérico', 'Dor e inflamação', 'Anti-inflamatório não esteroidal. Deve ser utilizado conforme orientação profissional.'),
('Ciclobenzaprina', 'ciclobenzaprina.png', 'Genérico', 'Dor muscular', 'Relaxante muscular utilizado em situações específicas de espasmo muscular.'),
('Butilbrometo de escopolamina + Dipirona', 'butilbrometo-de-escopolamina-dipirona.png', 'Genérico', 'Cólicas e dor', 'Combinação com ação antiespasmódica e analgésica, utilizada para cólicas e dores específicas.'),
('Dorflex Max', 'dorflex-max.png', 'Dorflex', 'Dor muscular', 'Medicamento utilizado para alívio de dores musculares.'),
('Cetirizina', 'cetirizina.png', 'Genérico', 'Alergia', 'Antialérgico utilizado em rinite alérgica, urticária e outras condições alérgicas.'),
('Fexofenadina', 'fexofenadina.png', 'Genérico', 'Alergia', 'Antialérgico utilizado principalmente em rinite alérgica e urticária.'),
('Loratadina', 'loratadina.png', 'Genérico', 'Alergia', 'Antialérgico utilizado para sintomas de rinite e outras manifestações alérgicas.'),
('Fluticasona', 'fluticasona.png', 'Genérico', 'Alergia nasal', 'Corticoide nasal utilizado principalmente no controle de sintomas de rinite.'),
('Acetilcisteína', 'acetilcisteina.png', 'Genérico', 'Tosse e secreções', 'Mucolítico que auxilia na fluidificação das secreções.'),
('Ambroxol', 'ambroxol.png', 'Genérico', 'Tosse e secreções', 'Expectorante utilizado para auxiliar na eliminação de secreções.'),
('Omeprazol', 'omeprazol.png', 'Genérico', 'Estômago e refluxo', 'Redutor da produção de ácido gástrico, utilizado em condições específicas de acidez e refluxo.'),
('Amoxicilina', 'amoxicilina.png', 'Genérico', 'Antibiótico', 'Antibiótico utilizado no tratamento de determinadas infecções bacterianas. Uso sob prescrição e orientação profissional.'),
('Hidróxido de alumínio + Hidróxido de magnésio', 'hidroxido-de-aluminio-hidroxido-de-magnesio.png', 'Genérico', 'Azia e acidez', 'Antiácido utilizado para alívio de sintomas relacionados à acidez gástrica.'),
('Amoxicilina + Clavulanato', 'amoxicilina-clavulanato.png', 'Genérico', 'Antibiótico', 'Antibiótico utilizado para determinadas infecções bacterianas. Uso sob prescrição e orientação profissional.'),
('Dimenidrinato', 'dimenidrinato.png', 'Genérico', 'Náusea e enjoo', 'Utilizado para prevenção e tratamento de náuseas, vômitos e enjoo em situações específicas.'),
('Metoclopramida', 'metoclopramida.png', 'Genérico', 'Náusea e vômito', 'Medicamento utilizado em situações específicas de náuseas e vômitos. Pode causar efeitos adversos neurológicos.'),
('Aciclovir', 'aciclovir.png', 'Genérico', 'Antiviral', 'Antiviral utilizado no tratamento de infecções causadas por determinados vírus, como herpes.'),
('Bisacodil', 'bisacodil.png', 'Genérico', 'Intestino', 'Laxante utilizado para tratamento de constipação em situações específicas.'),
('Albendazol', 'albendazol.png', 'Genérico', 'Antiparasitário', 'Antiparasitário utilizado no tratamento de determinadas verminoses.'),
('Cetoconazol', 'cetoconazol.png', 'Genérico', 'Antifúngico', 'Antifúngico utilizado no tratamento de determinadas infecções causadas por fungos.'),
('Anlodipino', 'anlodipino.png', 'Genérico', 'Hipertensão', 'Medicamento utilizado no tratamento da hipertensão e da angina em situações específicas.'),
('Hidroclorotiazida', 'hidroclorotiazida.png', 'Genérico', 'Hipertensão', 'Diurético utilizado principalmente no tratamento da hipertensão e de determinados edemas.'),
('Losartana', 'losartana.png', 'Genérico', 'Hipertensão', 'Medicamento utilizado principalmente no tratamento da hipertensão e em algumas condições cardiovasculares.'),
('Metformina', 'metformina.png', 'Genérico', 'Diabetes', 'Medicamento utilizado principalmente no tratamento do diabetes mellitus tipo 2.'),
('Sinvastatina', 'sinvastatina.png', 'Genérico', 'Colesterol', 'Medicamento utilizado para redução do colesterol e do risco cardiovascular em pacientes indicados.'),
('Levotiroxina', 'levotiroxina.png', 'Genérico', 'Tireoide', 'Hormônio utilizado para reposição hormonal em casos de hipotireoidismo.'),
('Adapaleno', 'adapaleno.png', 'Genérico', 'Pele e acne', 'Medicamento tópico utilizado principalmente no tratamento da acne.'),
('Atorvastatina', 'atorvastatina.png', 'Genérico', 'Colesterol', 'Medicamento utilizado para redução do colesterol e prevenção de eventos cardiovasculares em pacientes indicados.'),
('Peróxido de benzoíla', 'peroxido-de-benzoila.png', 'Genérico', 'Pele e acne', 'Medicamento tópico utilizado no tratamento da acne.'),
('Salbutamol', 'salbutamol.png', 'Genérico', 'Asma e broncoespasmo', 'Broncodilatador utilizado para alívio de broncoespasmo em condições respiratórias específicas.'),
('Budesonida', 'budesonida.png', 'Genérico', 'Asma e rinite', 'Corticoide utilizado no controle da inflamação das vias respiratórias em condições específicas.'),
('Fluoxetina', 'fluoxetina.png', 'Genérico', 'Antidepressivo', 'Medicamento de uso controlado, conforme prescrição médica.'),
('Lubrificante ocular', 'lubrificante-ocular.png', 'Genérico', 'Olhos', 'Utilizado para aliviar sintomas relacionados ao ressecamento ocular.');
