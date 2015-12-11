SELECT  8, `incre_position`, `description`, `created_at`, `updated_at`FROM `ei_version` WHERE `ei_scenario_id` = 7

insert into `ei_scenario`( `id_projet`, `nom_scenario`, `nb_joue`, `description`, `created_at`, `updated_at`)
SELECT  `id_projet`, `nom_scenario`, `nb_joue`, `description`, `created_at`, `updated_at` FROM `ei_scenario` WHERE `id` = 7

retrouver le 28
old_scenario=7;
new_scenario=28;

SELECT max( ID )
FROM `ei_scenario`
WHERE `id_projet` = (
SELECT id_projet
FROM ei_scenario
WHERE id =7 ) 

insert into `ei_version` (`ei_scenario_id`, `libelle`, `incre_position`, `description`, `created_at`, `updated_at`) 
SELECT  28 , `libelle`, `incre_position`, `description`, `created_at`, `updated_at` 
FROM `ei_version` WHERE ei_scenario_id = 7


insert into ei_fonction (`ei_version_id`, `ei_scenario_id`, `id_chemin`, `kal_fonction`, `observation`, `position`, `niveau`, `cp_id_kal`, `cp_ref_kal`, `created_at`, `updated_at`) 
SELECT 
(SELECT v.id from ei_version v , ei_version ov where v.ei_scenario_id = 28 and v.libelle = ov.libelle and ov.ei_scenario_id= 7 and ov.id = f.ei_version_id),
28, `id_chemin`, `kal_fonction`, `observation`, `position`, `niveau`, `cp_id_kal`, `cp_ref_kal`, `created_at`, `updated_at` FROM `ei_fonction` f
 WHERE `ei_scenario_id` =7

insert into ei_param (`id_fonction`, `ei_version_id`, `ei_scenario_id`, `kal_param`, `valeur`, `observation`, `created_at`, `updated_at`) 
SELECT 
(SELECT f.id from ei_fonction f , ei_fonction nf where f.ei_scenario_id = 28 and f.kal_fonction = nf.kal_fonction and nf.ei_scenario_id= 7 and nf.id = p.id_fonction),
(SELECT f2.ei_version_id from ei_fonction f2 , ei_fonction nf2 where f2.ei_scenario_id = 28 and f2.kal_fonction = nf2.kal_fonction and nf2.ei_scenario_id= 7 and nf2.id = p.id_fonction),
 28, `kal_param`, `valeur`, `observation`, `created_at`, `updated_at` FROM `ei_param` p
 WHERE `ei_scenario_id` =7



SELECT 
p.id_fonction as old_function,
(SELECT nf.`id` FROM `ei_fonction` nf,  `ei_fonction` ofu WHERE 
ofu.id = p.id_fonction
AND ofu.ei_scenario_id = 7
AND nf.ei_scenario_id = 28
AND nf.ei_version_id = (SELECT v.id from ei_version v , ei_version ov where v.ei_scenario_id = nf.ei_scenario_id and v.libelle = ov.libelle and ov.ei_scenario_id= ofu.ei_scenario_id and ov.id = ofu.ei_version_id)
AND nf.`kal_fonction` = ofu.kal_fonction
AND nf.`observation` = ofu.observation 
AND nf.`position` = ofu.`position`
AND nf.`cp_id_kal` = ofu.cp_id_kal 
AND nf.`cp_ref_kal` = ofu.cp_ref_kal
AND nf.`created_at` = ofu. created_at
AND nf.`updated_at` = ofu.updated_at) as new_function,
 p.ei_version_id  as old_version,
(SELECT v.id from ei_version v , ei_version ov where v.ei_scenario_id = 28 and v.libelle = ov.libelle and ov.ei_scenario_id= 7 and ov.id = p.ei_version_id) as   new_version,
 28, `kal_param`, `valeur`, `observation`, `created_at`, `updated_at` FROM `ei_param` p
 WHERE `ei_scenario_id` =7


------------------------- new 

-- scenarios
insert into `ei_scenario`( `id_projet`, `nom_scenario`, `nb_joue`, `description`, `created_at`, `updated_at`)
SELECT  `id_projet`, `nom_scenario`, `nb_joue`, `description`, `created_at`, `updated_at` FROM `ei_scenario` 
WHERE `id` = 7

--- version
insert into `ei_version` (`ei_scenario_id`, `libelle`, `incre_position`, `description`, `created_at`, `updated_at`) 
SELECT  ( SELECT max( ID ) FROM `ei_scenario` WHERE `id_projet` = (SELECT id_projet FROM ei_scenario WHERE id = p.ei_scenario_id )) , p.`libelle`,p.`incre_position`, p.`description`, p.`created_at`, p.`updated_at` 
FROM `ei_version` p  WHERE p.ei_scenario_id = 7


---- fonction
insert into ei_fonction (`ei_version_id`, `ei_scenario_id`, `id_chemin`, `kal_fonction`, `observation`, `position`, `niveau`, `cp_id_kal`, `cp_ref_kal`, `created_at`, `updated_at`) 
SELECT 
(SELECT v.id from ei_version v , ei_version ov where v.ei_scenario_id = ( SELECT max( ID ) FROM `ei_scenario` WHERE `id_projet` = (SELECT id_projet FROM ei_scenario WHERE id = f.ei_scenario_id )) and v.libelle = ov.libelle and ov.ei_scenario_id= f.ei_scenario_id and ov.id = f.ei_version_id),
( SELECT max( ID ) FROM `ei_scenario` WHERE `id_projet` = (SELECT id_projet FROM ei_scenario WHERE id = f.ei_scenario_id )), `id_chemin`, `kal_fonction`, `observation`, `position`, `niveau`, `cp_id_kal`, `cp_ref_kal`, `created_at`, `updated_at` FROM `ei_fonction` f
 WHERE `ei_scenario_id` =7


----- table des paramètres

INSERT INTO `ei_param`( `id_fonction`, `ei_version_id`, `ei_scenario_id`, `kal_param`, `valeur`, `observation`, `created_at`, `updated_at`) SELECT 
(SELECT nf.`id` FROM `ei_fonction` nf,  `ei_fonction` ofu WHERE 
ofu.id = p.id_fonction
AND ofu.ei_scenario_id = p.ei_scenario_id
AND nf.ei_scenario_id = ( SELECT max( ID ) FROM `ei_scenario` WHERE `id_projet` = (SELECT id_projet FROM ei_scenario WHERE id = p.ei_scenario_id ))
AND nf.ei_version_id = (SELECT v.id from ei_version v , ei_version ov where v.ei_scenario_id = nf.ei_scenario_id and v.libelle = ov.libelle and ov.ei_scenario_id= ofu.ei_scenario_id and ov.id = ofu.ei_version_id)
AND nf.`kal_fonction` = ofu.kal_fonction
AND nf.`observation` = ofu.observation 
AND nf.`position` = ofu.`position`
AND nf.`cp_id_kal` = ofu.cp_id_kal 
AND nf.`cp_ref_kal` = ofu.cp_ref_kal
AND nf.`created_at` = ofu. created_at
AND nf.`updated_at` = ofu.updated_at) as new_function,

(SELECT v.id from ei_version v , ei_version ov where v.ei_scenario_id = ( SELECT max( ID ) FROM `ei_scenario` WHERE `id_projet` = (SELECT id_projet FROM ei_scenario WHERE id = p.ei_scenario_id )) and v.libelle = ov.libelle and ov.ei_scenario_id=  p.ei_scenario_id and ov.id = p.ei_version_id) as   new_version,

 (SELECT max( ID ) FROM `ei_scenario` WHERE `id_projet` = (SELECT id_projet FROM ei_scenario WHERE id = p.ei_scenario_id )) as new_scenario, 
`kal_param`, 
`valeur`, 
`observation`, 
`created_at`, 
`updated_at` 
FROM `ei_param` p
 WHERE `ei_scenario_id` = 7

 