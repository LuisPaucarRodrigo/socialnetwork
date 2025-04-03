-- Insertar módulos
INSERT INTO `modules` (`id`, `name`, `type`, `parent_id`) VALUES
(1,'USERS_MODULE', 'module', NULL),
(2,'HR_MODULE', 'module', NULL),
(3,'INVENTORY_MODULE', 'module', NULL),
(4,'PURCHASING_MODULE', 'module', NULL),
(5,'PROJECT_MODULE', 'module', NULL),
(6,'FINANCE_MODULE', 'module', NULL),
(7,'BILLING_MODULE', 'module', NULL),
(8,'DOCUMENT_MODULE', 'module', NULL),
(9,'HUAWEI_MODULE', 'module', NULL),
(10,'CAR_MODULE', 'module', NULL);

-- Insertar submódulos con los ID de los módulos previamente insertados
INSERT INTO `modules` (`name`, `type`, `parent_id`) VALUES
('user_submodule', 'submodule', 1),  -- Parent: USERS_MODULE
('roles_submodule', 'submodule', 1),  -- Parent: USERS_MODULE

('hremployees_submodule', 'submodule', 2),  -- Parent: HR_MODULE
('hreemployees_submodule', 'submodule', 2),  -- Parent: HR_MODULE
('hrspreedsheet_submodule', 'submodule', 2),  -- Parent: HR_MODULE
('hrresdoc_submodule', 'submodule', 2),  -- Parent: HR_MODULE
('hrhrstate_submodule', 'submodule', 2),  -- Parent: HR_MODULE

('pprovider_submodule', 'submodule', 4),  -- Parent: PURCHASING_MODULE
('pprequest_submodule', 'submodule', 4),  -- Parent: PURCHASING_MODULE
('pporder_submodule', 'submodule', 4),  -- Parent: PURCHASING_MODULE
('ppcpurchase_submodule', 'submodule', 4),  -- Parent: PURCHASING_MODULE

('pclients_submodule', 'submodule', 5),  -- Parent: PROJECT_MODULE
('ppro_submodule', 'submodule', 5),  -- Parent: PROJECT_MODULE
('pprepint_submodule', 'submodule', 5),  -- Parent: PROJECT_MODULE
('pprepext_submodule', 'submodule', 5),  -- Parent: PROJECT_MODULE
('ppropint_submodule', 'submodule', 5),  -- Parent: PROJECT_MODULE
('ppropext_submodule', 'submodule', 5),  -- Parent: PROJECT_MODULE
('pchecklist_submodule', 'submodule', 5),  -- Parent: PROJECT_MODULE
('padmexpen_submodule', 'submodule', 5),  -- Parent: PROJECT_MODULE
('pbacklog_submodule', 'submodule', 5),  -- Parent: PROJECT_MODULE

('fbudget_submodule', 'submodule', 6),  -- Parent: FINANCE_MODULE
('fpapproval_submodule', 'submodule', 6),  -- Parent: FINANCE_MODULE
('fdeposists_submodule', 'submodule', 6),  -- Parent: FINANCE_MODULE
('fpopayment_submodule', 'submodule', 6),  -- Parent: FINANCE_MODULE
('faccstatement_submodule', 'submodule', 6),  -- Parent: FINANCE_MODULE

('billingpint_submodule', 'submodule', 7),  -- Parent: BILLING_MODULE
('billingpext_submodule', 'submodule', 7),  -- Parent: BILLING_MODULE

('cchanappro_submodule', 'submodule', 10),  -- Parent: CAR_MODULE
('cmobileunit_submodule', 'submodule', 10);  -- Parent: CAR_MODULE



-- Insertar el rol Administrador
INSERT INTO roles (id, name, description, created_at, updated_at)
VALUES
(1, 'Administrador', 'Acceso total a todos los módulos y submódulos', NOW(), NOW());



-- Insertar acceso a todos los módulos
INSERT INTO `module_role` (role_id, module_id, created_at, updated_at)
SELECT
    1,  -- El ID del rol "Administrador"
    m.id,  -- El ID de cada módulo
    NOW(), NOW()
FROM `modules` m
WHERE m.type = 'module';
-- Insertar acceso a todos los submódulos
INSERT INTO `module_role` (role_id, module_id, created_at, updated_at)
SELECT
    1,  -- El ID del rol "Administrador"
    m.id,  -- El ID de cada módulo
    NOW(), NOW()
FROM `modules` m
WHERE m.type = 'submodule';


