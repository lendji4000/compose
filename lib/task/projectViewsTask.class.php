<?php

class projectViewsTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = 'create';
        $this->name = 'views';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [projectViews|INFO] task does things.
Call it with:

  [php symfony projectViews|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
//    $databaseManager = new sfDatabaseManager($this->configuration);
//    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', $options['env'], true);
        sfContext::createInstance($configuration);
        $conn = Doctrine_Manager::connection();
        //Création de la vue sql pour la gestion des migrations de fonctions
        $conn->execute(" DROP  TABLE IF EXISTS  ei_user_bugs_vw");
        $conn->execute("CREATE OR REPLACE VIEW  ei_delivery_functions_vw 
            (t_id,t_obj_id,t_ref_obj,t_name,t_path,t_type,t_project_id,t_project_ref,
            f_function_id,f_function_ref,f_criticity,
            sc_script_id,sc_function_id,sc_function_ref ,sc_ticket_id,sc_ticket_ref,
            s_id,s_name,s_delivery_id,s_package_id,s_package_ref,s_project_id ,s_project_ref,
			et_ticket_id,et_ticket_ref,et_name)
             AS
            (SELECT  t.id ,t.obj_id,t.ref_obj,t.name,t.path,t.type,t.project_id,t.project_ref,
            f.function_id,f.function_ref ,f.criticity,
            sc.script_id,sc.function_id,sc.function_ref ,sc.ticket_id,sc.ticket_ref,
            s.id,s.name,s.delivery_id,s.package_id ,s.package_ref,s.project_id ,s.project_ref,
			et.ticket_id,et.ticket_ref,et.name FROM 
            `ei_tree` t 
            left join kal_function f on t.obj_id=f.function_id and t.ref_obj=f.function_ref
            left join ei_script sc on t.obj_id=sc.function_id and t.ref_obj=sc.function_ref 
            left join ei_subject s on s.package_id=sc.ticket_id and s.package_ref=sc.ticket_ref
            left join ei_ticket et on et.ticket_id=sc.ticket_id And et.ticket_ref=sc.ticket_ref )");
        $this->log("Création de la vue permettant de récupérer les bugs traités par chaque user et par statusts");
        /* Vue des bugs avec toutes les relations autour */
        $conn->execute("CREATE OR REPLACE VIEW ei_subjects_with_relations_vw
                (id,author_id,delivery_id,type_id,state_id,priority_id,project_id,project_ref,name,description,external_id,created_at,updated_at,package_id,package_ref,
                development_time,development_estimation,test_time,test_estimation,expected_date,
                auth_name, del_name,type_name,ss_name,ss_color_code,sp_name,assign_id,assign_name )
                AS
                (select s.id,s.author_id,s.delivery_id,s.subject_type_id,s.subject_state_id,s.subject_priority_id,s.project_id,s.project_ref,s.name,s.description,s.alternative_system_id,
                s.created_at,s.updated_at,s.package_id,s.package_ref, s.development_time,s.development_estimation,s.test_time,s.test_estimation,s.expected_date ,
                auth.username ,del.name,st.name,ss.name,ss.color_code,sp.name,g.id,g.username
                from ei_subject s
                left join sf_guard_user auth on auth.id=s.author_id
                left join ei_delivery del on del.id=s.delivery_id
                left join ei_subject_type st on st.id=s.subject_type_id
                left join ei_subject_state ss on ss.id=s.subject_state_id
                left join ei_subject_priority sp on sp.id=s.subject_priority_id
                left join ei_subject_assignment sa on sa.subject_id=s.id
                left join sf_guard_user g on g.id=sa.guard_id ) ");
        
        
         
        $conn->execute("CREATE OR REPLACE VIEW ei_user_bugs_vw
                (st_id,st_project_id,st_project_ref ,st_name,st_color_code,g_id,username,email_address,s_id,s_name,nbBugs)
                AS
                (select st.id,st.project_id,st.project_ref,st.name, st.color_code,
                g.id,g.username,g.email_address,  s.id,s.name, count(s.id)   from ei_subject_state st 
                left join ei_subject s on st.id=s.subject_state_id 
                left join ei_subject_assignment ss on ss.subject_id=s.id
                left join sf_guard_user g on g.id=ss.guard_id  group by g.id , st.id)");
        /* Création des fonctions impactés dans une livraison */
        $conn->execute("CREATE OR REPLACE VIEW  ei_delivery_impacted_functions_vw 
            (t_id,t_obj_id,t_ref_obj,t_name,t_path,t_type,t_project_id,t_project_ref,
            f_function_id,f_function_ref,f_criticity,
            sc_script_id,sc_function_id,sc_function_ref ,sc_ticket_id,sc_ticket_ref,
            s_id,s_name,s_delivery_id,s_package_id,s_package_ref,s_project_id ,s_project_ref,
			et_ticket_id,et_ticket_ref,et_name ,
            s2_id, s2_delivery_id, s2_name,sf_automate)
             AS
            (SELECT  t.id ,t.obj_id,t.ref_obj,t.name,t.path,t.type,t.project_id,t.project_ref,
            f.function_id,f.function_ref ,f.criticity,
            sc.script_id,sc.function_id,sc.function_ref ,sc.ticket_id,sc.ticket_ref,
            s.id,s.name,s.delivery_id,s.package_id ,s.package_ref,s.project_id ,s.project_ref,
			et.ticket_id,et.ticket_ref,et.name,
             s2.id ,s2.delivery_id, s2.name , sf.automate FROM 
            `ei_tree` t 
            left join kal_function f on t.obj_id=f.function_id and t.ref_obj=f.function_ref
             left join ei_subject_functions sf on t.obj_id=sf.function_id and t.ref_obj=sf.function_ref
             left join ei_subject s2 on s2.id=sf.subject_id
            left join ei_script sc on t.obj_id=sc.function_id and t.ref_obj=sc.function_ref 
            left join ei_subject s on s.package_id=sc.ticket_id and s.package_ref=sc.ticket_ref
            left join ei_ticket et on et.ticket_id=sc.ticket_id And et.ticket_ref=sc.ticket_ref )
            
            ");
        $this->log('Vue des fonctions à migrer d une livraison sql créée');
        
        /* Vue de récupération des bugs d'une fonction */
        $this->log("Vue des bugs d'une fonction ");
        $conn->execute("CREATE OR REPLACE VIEW ei_function_bugs_vw

          (  subject_id,author_id,delivery_id,type_id,state_id,priority_id,project_id,project_ref,subject_name,decription,alternative_id,package_id,package_ref,created_at,updated_at,
          development_time,development_estimation,test_time,test_estimation,expected_date ,
            function_id,function_ref,author_name,state_name,priority_name,type_name,delivery_name,assign_username,coverage )
          AS
         (select s.id,s.author_id,s.delivery_id,s.subject_type_id,s.subject_state_id,s.subject_priority_id,s.project_id,s.project_ref,s.name,s.description,s.alternative_system_id,
                s.package_id,s.package_ref,s.created_at,s.updated_at, s.development_time,s.development_estimation,s.test_time,s.test_estimation,s.expected_date ,
                sf.function_id,sf.function_ref,auth.username as author,st.name as st_name,sp.name as sp_name,stt.name as stt_name,d.name as d_name,g.username,c.coverage   from ei_subject s
            left join ei_subject_functions sf on sf.subject_id=s.id
            left join ei_subject_state st on st.id=s.subject_state_id
            left join ei_delivery d on d.id=s.delivery_id
            left join ei_subject_priority sp on sp.id=s.subject_priority_id
            left join ei_subject_type stt on stt.id=s.subject_type_id
            left join ei_subject_assignment sas on sas.subject_id=s.id
            left join sf_guard_user g on g.id=sas.guard_id
            left join sf_guard_user auth on auth.id=s.author_id
            left join ei_subject_has_campaign scp on scp.subject_id=s.id and is_tnr=1
            left join ei_campaign c on c.id=scp.campaign_id ) 

            UNION

            (select s.*,sc.function_id,sc.function_ref,auth.username as author,st.name as st_name,sp.name as sp_name,stt.name as stt_name,d.name as d_name,g.username,c.coverage from ei_subject s 
                left join ei_ticket t on t.ticket_id=s.package_id and t.ticket_ref=s.package_ref
                left join ei_script sc on sc.ticket_id=s.package_id and sc.ticket_ref=s.package_ref
                left join ei_subject_state st on st.id=s.subject_state_id
                left join ei_delivery d on d.id=s.delivery_id
                left join ei_subject_priority sp on sp.id=s.subject_priority_id
                left join ei_subject_type stt on stt.id=s.subject_type_id
                left join ei_subject_assignment sas on sas.subject_id=s.id
                left join sf_guard_user g on g.id=sas.guard_id
                left join sf_guard_user auth on auth.id=s.author_id
                left join ei_subject_has_campaign scp on scp.subject_id=s.id and is_tnr=1
                left join ei_campaign c on c.id=scp.campaign_id ) ");
        $this->log("Vue des bugs d'une fonction créée ");
        //Création de la vue sql pour la getion des migrations de scénario
        $conn->execute("CREATE OR REPLACE VIEW  ei_delivery_scenario_vw 
            (sc_id,sc_ei_node_id,sc_project_id,sc_project_ref ,sc_name,sc_description,
            sp_ei_scenario_id,sp_package_id,sp_package_ref,sp_ei_version_id ,
            s_id,s_name,s_delivery_id,s_package_id,s_package_ref,s_project_id ,s_project_ref,  
			et_ticket_id,et_ticket_ref,et_name)
             AS
            (SELECT  sc.id ,sc.ei_node_id,sc.project_id,sc.project_ref,sc.nom_scenario,sc.description, 
                sp.ei_scenario_id,sp.package_id,sp.package_ref,sp.ei_version_id ,
                s.id,s.name,s.delivery_id,s.package_id ,s.package_ref,s.project_id ,s.project_ref,
			et.ticket_id,et.ticket_ref,et.name FROM 
            ei_scenario sc 
            left join ei_scenario_package sp on sp.ei_scenario_id=sc.id And  sp.package_id IS NOT NULL And sp.package_ref IS NOT NULL
            left join ei_subject s  on s.package_id=sp.package_id And s.package_ref=s.package_ref And  s.package_id IS NOT NULL And s.package_ref IS NOT NULL
            left join ei_ticket et on et.ticket_id=s.package_id And et.ticket_ref=s.package_ref And   et.ticket_ref IS NOT NULL And et.ticket_id IS NOT NULL )");
        $this->log('Vue des fonctions à migrer d une livraison sql créée');

        $conn->execute("CREATE OR REPLACE VIEW ei_subject_assignment_vw
          (sah_subject_id,sah_author_of_assignment,sah_assign_to,sah_date,sah_is_assignment, s_name, s_desc, uas_username, uau_username)
          AS
         ( SELECT sah.subject_id,sah.author_of_assignment,sah.assign_to,sah.date,sah.is_assignment ,s.name ,s.description , uas.username  , uau.username 
         FROM `ei_subject_assignment_history` sah 
          left join ei_subject s on s.id=sah.subject_id 
          left join sf_guard_user uas on uas.id=sah.assign_to 
          left join sf_guard_user uau on uau.id=sah.author_of_assignment 
           )");
         $this->log('Vue sql des historiques d assignations creee');
         
        $this->log("Création de la vue permettant de récuperer les logs d'une fonction ");
        $conn->execute("CREATE OR REPLACE VIEW ei_test_set_function_vw
          (num_ex, iteration_id,tsf_ts_id,tsf_function_id, tsf_function_ref ,tsf_date_debut, tsf_date_fin, tsf_status,tsf_duree,
                        tsfs_project_id,tsfs_project_ref,tsfs_name,tsfs_color_code,tsfs_state_code,
                        ts_profile_id, ts_profile_ref,ts_scenario_id,  ts_ei_version_id,ts_ei_data_set_id,ts_mode , ts_author_id,
                        v_name,  s_name,
                        dt_name,p_name,
                        lf_id,lp_id,lf_duree,lp_param_id,lp_param_name,lp_param_valeur,lp_param_type,  
                        g_username, g_first_name,  g_last_name, g_email)
          AS
         (SELECT tsf.id ,tsf.iteration_id,tsf.ei_test_set_id , tsf.function_id, tsf.function_ref, tsf.date_debut , tsf.date_fin ,tsf.status,tsf.duree ,
                        tsfs.project_id,tsfs.project_ref,tsfs.name ,tsfs.color_code ,tsfs.state_code ,
                        ts.profile_id , ts.profile_ref, ts.ei_scenario_id , ts.ei_version_id,ts.ei_data_set_id, ts.mode  ,ts.author_id ,
                        v.libelle , s.nom_scenario,
                        dt.name ,p.name,
                        lf.id,lp.id,lf.duree,lp.param_id,lp.param_name,lp.param_valeur,lp.param_type,  
                        g.username ,g.first_name, g.last_name,g.email_address
                        from ei_test_set_function tsf 
                        left join ei_test_set_state tsfs on UPPER(CASE WHEN (tsf.status = 'blank')  THEN 'AB' ELSE tsf.status END)=tsfs.state_code
                        left join ei_test_set ts on ts.id=tsf.ei_test_set_id 
                        left join ei_version v on v.id=ts.ei_version_id 
                        left join ei_scenario s on s.id=ts.ei_scenario_id 
                        left join ei_data_set dt on dt.id=ts.ei_data_set_id 
                        left join ei_log_function lf on lf.ei_test_set_function_id=tsf.id
                        left join ei_log_param lp on lp.ei_log_function_id =lf.id  
                        left join ei_profil p on p.profile_id=ts.profile_id and p.profile_ref=ts.profile_ref
                        left join sf_guard_user g on g.id=ts.author_id
                        order by tsf.id desc
           )");  
        
         $this->log("Vue sql des executions de fonction sans les paramètres d'execution ...");
         
         $this->log("Création de la vue permettant de récuperer les logs d'une fonction ");
        $conn->execute("CREATE OR REPLACE VIEW ei_test_set_function_simply_vw
          (num_ex, tsf_ts_id,tsf_function_id, tsf_function_ref ,tsf_date_debut, tsf_date_fin, tsf_status,
                        tsfs_project_id,tsfs_project_ref,tsfs_name,tsfs_color_code,tsfs_state_code,
                        ts_profile_id, ts_profile_ref,ts_scenario_id,  ts_ei_version_id,ts_mode ,ts_termine, ts_author_id )
          AS
         (SELECT tsf.id ,tsf.ei_test_set_id , tsf.function_id, tsf.function_ref, tsf.date_debut , tsf.date_fin ,tsf.status ,
                        tsfs.project_id,tsfs.project_ref,tsfs.name ,tsfs.color_code ,tsfs.state_code ,
                        ts.profile_id , ts.profile_ref, ts.ei_scenario_id , ts.ei_version_id, ts.mode , ts.termine , ts.author_id  
                        from ei_test_set_function tsf 
                        left join ei_test_set_state tsfs on  UPPER(CASE WHEN (tsf.status = 'blank')  THEN 'AB' ELSE tsf.status END)=tsfs.state_code
                        left join ei_test_set ts on ts.id=tsf.ei_test_set_id 
           )");  
        
         $this->log("Vue sql des executions de fonction sans les paramètres d'execution créee...");
       

        $conn->execute("
      CREATE OR REPLACE VIEW ei_test_set_status_vw
      (id,profile_ref,profile_id,ei_scenario_id,ei_version_id,ei_data_set_id,termine,created_at,updated_at,author_id,mode,device,duree,status_nom,status_color,nb_fct,nb_fct_executees) AS
      (
        SELECT ts.id, ts.profile_ref, ts.profile_id, ts.ei_scenario_id, ts.ei_version_id, ts.ei_data_set_id, ts.termine, ts.created_at, ts.updated_at, ts.author_id,
            ts.mode, ts.device,SUM(tsf.duree) as duree, st.name, st.color_code, COUNT(*) as nbFonctions,
            SUM(CASE WHEN tsf.status = 'ko' OR tsf.status = 'ok' THEN 1 else 0 end) as nbFonctionsExecutees
        FROM  ei_test_set ts, ei_test_set_function tsf, ei_test_set_state st, ei_scenario sc
        WHERE st.state_code = ts.status
        AND sc.id = ts.ei_scenario_id
        AND st.project_id = sc.project_id
        AND st.project_ref = sc.project_ref
        AND ts.id = tsf.ei_test_set_id
        GROUP BY ts.id
      )"); 
      $this->log('Vue sql des statuts de JDT créée');

      $conn->execute("
      CREATE OR REPLACE VIEW ei_campaign_duration_vw
      (execution_id, duree) AS
      (
        SELECT exg.execution_id, SUM(duree) as duree
        FROM ei_campaign_execution_graph exg, ei_test_set_function tsf
        WHERE tsf.ei_test_set_id = exg.ei_test_set_id
        GROUP BY exg.execution_id
      )
      ");
      /* Création de triggers permettant de mettre à jour l'historique des résolutions de conflits */
      $this->log("Création de triggers permettant de mettre à jour l'historique des résolutions de conflits "); 
      $conn->execute("DROP TRIGGER IF EXISTS set_function_history_conflict");
      $conn->execute("DROP TRIGGER IF EXISTS set_scenario_history_conflict");
      //Trigger d'historisation des resolutions de conflits sur les fonctions
      $conn->execute(" 
            CREATE TRIGGER `set_function_history_conflict` BEFORE INSERT ON `ei_package_function_conflict`
            FOR EACH ROW BEGIN 
                INSERT INTO ei_package_function_conflict_history (function_id, function_ref, delivery_id,resolved_date,package_id,package_ref,resolved_author,created_at,updated_at)
                    VALUES (NEW.function_id,NEW.function_ref,NEW.delivery_id,NEW.resolved_date,NEW.package_id,NEW.package_ref,NEW.resolved_author,NEW.created_at,NEW.updated_at)
                    ON DUPLICATE KEY UPDATE function_id=function_id,function_ref=function_ref,delivery_id=delivery_id,resolved_date=resolved_date;
              END
            ");
      //Trigger d'historisation des resolutions de conflits sur les scenarios
      $conn->execute(" 
            CREATE TRIGGER `set_scenario_history_conflict` BEFORE INSERT ON `ei_package_scenario_conflict`
            FOR EACH ROW BEGIN 
                INSERT INTO ei_package_scenario_conflict_history (ei_scenario_id, delivery_id,resolved_date,package_id,package_ref,resolved_author,created_at,updated_at)
                    VALUES (NEW.ei_scenario_id,NEW.delivery_id,NEW.resolved_date,NEW.package_id,NEW.package_ref,NEW.resolved_author,NEW.created_at,NEW.updated_at)
                    ON DUPLICATE KEY UPDATE ei_scenario_id=ei_scenario_id,delivery_id=delivery_id,resolved_date=resolved_date;
              END
            ");
      //$conn->execute("|   delimiter ");
       $this->log("Fin de création de trigger pour résolutions de conflits dans les migrations de livraison "); 
       
      $conn->execute("
      CREATE OR REPLACE VIEW ei_campaign_status_vw
      (id,profile_id,profile_ref,project_id,project_ref,author_id,author_username,campaign_id,termine,on_error,created_at,updated_at,duree,status_nom,status_color,nb_step_ex,nb_step_camp,nb_step_executed) AS
      (
        SELECT ex.id,ex.profile_id,ex.profile_ref,ex.project_id,ex.project_ref,ex.author_id, COALESCE(sfg.username,'') as username,ex.campaign_id,
        ex.termine,ex.on_error, ex.created_at,ex.updated_at, exd.duree, st.name, st.color_code,
        COUNT(exg.execution_id), (SELECT COUNT(*) FROM ei_campaign_graph WHERE campaign_id = ex.campaign_id), COUNT(exg.ei_test_set_id)

        FROM ei_campaign_execution_graph exg, ei_campaign_duration_vw exd, ei_test_set_state st,
        ei_campaign_execution ex
        LEFT JOIN sf_guard_user sfg ON (ex.author_id = sfg.id)

        WHERE ex.id = exg.execution_id
        AND ex.id = exd.execution_id
        AND st.project_ref = ex.project_ref
        AND st.project_id = ex.project_id

        GROUP BY exg.execution_id, ex.id, st.state_code
        HAVING st.state_code = (CASE
        WHEN SUM(CASE WHEN (exg.state ='Processing' OR exg.state = 'Blank') AND ex.termine = 0 THEN 1 else 0 end ) > 0 THEN 'NA'
        WHEN SUM(CASE WHEN exg.state ='Ko' THEN 1 else 0 end ) > 0 THEN 'KO'
        WHEN SUM(CASE WHEN exg.state ='Aborted' THEN 1 else 0 end ) > 0 THEN 'AB'
        WHEN SUM(CASE WHEN (exg.state ='Processing' OR exg.state = 'Blank') AND ex.termine = 1 THEN 1 else 0 end ) > 0 THEN 'AB'
        ELSE 'OK' END)
        ORDER BY ex.id
      )");

        $this->log('Vue sql des statuts de Campagne créée');

        
        
        
        
        /* Vue de purge des sujets avec des tickets inexistants */
        $conn->execute("CREATE OR REPLACE VIEW  ei_subject_repare_migration_vw 
            ( s_id,s_name,s_package_id,s_package_ref,
             t_ticket_id,t_ticket_ref,t_name,
	     t2_ticket_id,t2_ticket_ref,t2_name)
             AS
            (SELECT   s.id,s.name,s.package_id ,s.package_ref ,
                        t.ticket_id,t.ticket_ref,t.name,
			t2.ticket_id,t2.ticket_ref,t2.name FROM   
            ei_subject s LEFT OUTER JOIN
            ei_ticket t ON t.ticket_id=s.package_id AND t.ticket_ref=s.package_ref LEFT OUTER JOIN
            ei_ticket t2 ON t2.name=CONCAT('Package_S',s.id) 
            where s.package_id is not null AND t2.ticket_id IS NOT NULL And  t.ticket_id IS NULL )"); 
        $this->log('Vue creee');
        
        /* Création de la vue permettant de récupérerer le process d'une livraison */
        $conn->execute("CREATE OR REPLACE VIEW  ei_delivery_process_vw ( subject_id,subject_name,delivery_id, sm_id, sm_migration,d_id)
                    AS
                    ( SELECT  s.id ,s.name,s.delivery_id, sm.id ,sm.migration , d.id 
                    FROM ei_subject s inner join ei_delivery d on s.delivery_id=d.id 
                    inner join ei_subject_migration sm on sm.subject_id=s.id   )");
        $this->log('Vue de gestion du process de migration d une livraison creee');
        
        /* Vue de récupération des scénarios dans lesquels une fonction et utilisé */
        $conn->execute("CREATE OR REPLACE VIEW  ei_scenarios_function_vw 
            (kf_function_id,kf_function_ref,kf_project_id,kf_project_ref,fonction_id,vs_id,v_id,v_libelle,s_id,s_nom_scenario,s_ei_node_id)
             AS
            (SELECT   kf.function_id,kf.function_ref,kf.project_id,kf.project_ref,f.id,vs.id,v.id,v.libelle,s.id,s.nom_scenario,s.ei_node_id  FROM   
            kal_function kf             
            INNER  JOIN ei_fonction f on kf.function_id=f.function_id and kf.function_ref=f.function_ref  
            INNER  JOIN ei_version_structure vs on vs.id=f.ei_version_structure_id 
            INNER  JOIN ei_version v  on v.id=vs.ei_version_id
            INNER  JOIN ei_scenario s on s.id=v.ei_scenario_id  )"); 
        $this->log('Vue creee');
        
        /* Création de la vue de récupération des fonctions non exécutées sur une campagne */
        $this->log("Vue des fonctions non exécutées au sein d'un campagne de tests");
        $conn->execute("CREATE OR REPLACE VIEW ei_unexecuted_functions_vw

          (  t_id, t_obj_id,  t_ref_obj,t_project_id ,t_project_ref, function_name ,t_path,criticity  ,ceg_execution_id ,tsf_id,nbSubject,nbSubOpen )
          AS
         (select t.id , t.obj_id,  t.ref_obj ,t.project_id ,t.project_ref,t.name  ,t.path ,k.criticity   ,ceg.execution_id, tsf.id as tsf_id, count(DISTINCT(sb.id))  , COUNT(DISTINCT  case when st.close_del_state=0 then sb.id else 0 end) 
            from ei_tree t
            inner join kal_function k on t.obj_id=k.function_id and t.ref_obj=k.function_ref

            inner join ei_script s on s.function_id=k.function_id and s.function_ref=k.function_ref
            inner join ei_subject sb on sb.package_id=s.ticket_id and sb.package_ref=s.ticket_ref
            inner join ei_subject_state st on st.id= sb.subject_state_id
            left join ei_test_set_function tsf on tsf.function_id=t.obj_id and tsf.function_ref=t.ref_obj 
            left join ei_campaign_execution_graph ceg on ceg.ei_test_set_id=tsf.ei_test_set_id 
            group by t.id ) 

            UNION

            (select t.id , t.obj_id,  t.ref_obj  ,t.project_id ,t.project_ref,t.name  ,t.path ,k.criticity   ,ceg.execution_id,tsf.id as tsf_id,   count(DISTINCT(sb.id))  , COUNT(DISTINCT  case when st.close_del_state=0 then sb.id else 0 end) 
          	from ei_tree t
            inner join kal_function k on t.obj_id=k.function_id and t.ref_obj=k.function_ref

            inner join ei_subject_functions sf  on sf.function_id=k.function_id and sf.function_ref=k.function_ref 
            inner join ei_subject sb on sb.id=sf.subject_id
            left join ei_script s on s.ticket_id=sb.package_id and s.ticket_ref=sb.package_ref 

            inner join ei_subject_state st on st.id= sb.subject_state_id
            left join ei_test_set_function tsf on tsf.function_id=t.obj_id and tsf.function_ref=t.ref_obj 
            left join ei_campaign_execution_graph ceg on ceg.ei_test_set_id=tsf.ei_test_set_id
            where    s.script_id is NULL 


            group by t.id) ");
        $this->log("Fin de la vue ");
        
        
        
        /* Gestion des vues de récupération des indicateurs de la livraison */
        //récupération de toutes les fonctions impactées d'une livraison
        $conn->execute("CREATE OR REPLACE VIEW  ei_delivery_impacted_functions_all_vw 
            (t_id,t_obj_id,t_ref_obj,t_name,t_path,t_type,t_project_id,t_project_ref,
            f_function_id,f_function_ref,f_criticity, s_delivery_id )
             AS
             (select t_id,t_obj_id,t_ref_obj,t_name,t_path,t_type,t_project_id,t_project_ref,
            f_function_id,f_function_ref,f_criticity, s_delivery_id from ei_delivery_impacted_functions_vw )  
            union   
             (select t_id,t_obj_id,t_ref_obj,t_name,t_path,t_type,t_project_id,t_project_ref,
                       f_function_id,f_function_ref,f_criticity,  s2_delivery_id as s_delivery_id  from ei_delivery_impacted_functions_vw  )");
        
        //Récupération des fonctions (distinctes) impactées d'une livraison
        $conn->execute(" CREATE OR REPLACE VIEW  ei_delivery_impacted_functions_distinct_vw 
            (t_id,t_obj_id,t_ref_obj,t_name,t_path,t_type,t_project_id,t_project_ref,
            f_function_id,f_function_ref,f_criticity,  s_delivery_id)
             AS
             (select distinct t_id,t_obj_id,t_ref_obj,t_name,t_path,t_type,t_project_id,t_project_ref,
            f_function_id,f_function_ref,f_criticity,  s_delivery_id from ei_delivery_impacted_functions_all_vw ) ");
        
        // Vue de récupération du nombre d'occurences d'exécution des paramètres d'une fonction
        $conn->execute(" create or replace view ei_log_param_count_distinct_vw
                        (iteration_id,function_id,function_ref ,param_id,param_type,  param_count_in )
                        as
                         (select  iteration_id,function_id,function_ref  ,param_id,param_type,count(distinct(param_valeur)) as param_count_in
                         from ei_log_param   
                         group by iteration_id,function_id,function_ref ,param_id,param_type )");
        
        //Récupération des occurences d'utilisation distinctes d'un paramètre lors d'une exécution ( nombres de valeurs distinctes utilisées pour le paramètre)
        $conn->execute(" create or replace view ei_log_param_in_count_distinct_min_vw 
                (iteration_id,function_id,function_ref , nbMinDistinctParams)
                as
                (select iteration_id,function_id,function_ref   ,MIN(param_count_in)  
                from ei_log_param_count_distinct_vw 
                where param_type='IN'
                group by iteration_id,function_id,function_ref) ");
        
        /* Création et suppression de l'index sur la table ei_log_param permettant de requeter plus vite */
        $this->log("Création et suppression de l'index sur la table ei_log_param permettant de requeter plus vite ");
         $exist_indexes=$conn->fetchAll(" SHOW INDEX FROM ei_log_param WHERE KEY_NAME = 'group_by_distinct_value_idx' "); 
         if(count($exist_indexes)> 0):
             $conn->execute("DROP  INDEX group_by_distinct_value_idx ON ei_log_param");
         endif; 
        $conn->execute("create index group_by_distinct_value_idx on ei_log_param (iteration_id,function_id,function_ref ,param_id,param_type)");
        $this->log("Fin de la création de l index ... ");
        //Récupération de chacune des fonctions avec pour chacune d'elles le nombre d'exécution 'ok' et 'ko'
        $conn->execute("
            create or replace view ei_log_function_nb_ex_vw 
            (iteration_id,function_id,function_ref ,  nbExOK, nbExKo)
            as 

            (SELECT iteration_id,function_id,function_ref ,SUM(IF(status='ok',1,0)) as nbExOK,SUM(IF(status='ko',1,0)) as nbExKo
            FROM  ei_log_function group by iteration_id , function_id ,function_ref)");
        
        /* Récupération des exécutions de fonctions impactées d'une livraison avec à chaque fois la variabilisation minimale de chaque fonctions impactée */
         $conn->execute("
            create or replace view ei_impacted_functions_stats_with_params_vw
            (delivery_id ,  function_id , function_ref ,criticity,iteration_id,nbExOk,nbExKo, nbMinDistinctParams )
            as 

            ( select del.s_delivery_id ,  del.f_function_id , del.f_function_ref,del.f_criticity,  exec.iteration_id,exec.nbExOk,exec.nbExKo, param.nbMinDistinctParams 
            from ei_delivery_impacted_functions_distinct_vw del  
            left   join ei_log_function_nb_ex_vw exec on exec.function_id=del.f_function_id and exec.function_ref=del.f_function_ref 
            left   join ei_log_param_in_count_distinct_min_vw param on param.function_id=exec.function_id and param.function_ref=exec.function_ref and param.iteration_id=exec.iteration_id)");
        
         /* Création de la vue utile pour la récupération des statistiques d'exécutions de fonctions pour une exécution de campagne.
            Cette vue ne contient pas les rapports sur les bugs liés aux fonctions ou non  */
         $conn->execute("create or replace view campaign_execution_stat_vw 
                        (execution_id,iteration_id,test_set_function_id ,  ei_test_set_id , function_id,function_ref ,tr_id ,tr_obj_id,tr_ref_obj, function_name,tr_path,criticity,
                        nbEx,nbExOk,nbExKo, avg_time,max_time,min_time,project_id,project_ref )
                        as 

                        SELECT cep.execution_id, tsf.iteration_id,tsf.id , tsf.ei_test_set_id , tsf.function_id ,tsf.function_ref,tr.id, tr.obj_id , tr.ref_obj,tr.name ,tr.path,k.criticity,
                        count(tsf.id),SUM(IF(UPPER(tsf.status)='OK',1,0)),SUM(IF(UPPER(tsf.status)='KO',1,0)),AVG(CAST(duree AS SIGNED)),MAX(CAST(duree AS SIGNED)),MIN(CAST(duree AS SIGNED)),
                        tr.project_id,tr.project_ref
                        from ei_test_set_function as tsf
                        left join ei_test_set ts on ts.id=tsf.ei_test_set_id
                        left join ei_campaign_execution_graph cep on cep.ei_test_set_id=ts.id 
                        left join ei_tree tr on tr.obj_id=tsf.function_id and tr.ref_obj=tsf.function_ref
                        left join kal_function as k on k.function_id=tr.obj_id and k.function_ref=tr.ref_obj

                         group by cep.execution_id,tsf.function_id,tsf.function_ref ");
         /* création  de la vue permettant de récupérerer pour une intervention , toutes les fonctions liées et le statut associé à l'intervention  */
         
         $conn->execute("create or replace view intervention_impacts_vw 
                        (id ,project_id,project_ref,delivery_id ,name,s_created_at,subject_state_name,close_del_state,function_id,function_ref) 
                         as
                        select s.id,s.project_id,s.project_ref,s.delivery_id,s.name,s.created_at,ss.name as subject_state_name, ss.close_del_state as close_del_state, sc.function_id, sc.function_ref 
                        
                        from ei_subject s 
                        inner join ei_script sc on s.package_id=sc.ticket_id and s.package_ref=sc.ticket_ref
                        inner join ei_subject_state ss on ss.id=s.subject_state_id
                        union 
                        select s.id,s.project_id,s.project_ref,s.delivery_id,s.name,s.created_at,ss.name as subject_state_name, ss.close_del_state as close_del_state,  sf.function_id, sf.function_ref from ei_subject s 
                        inner join ei_subject_functions sf on s.id=sf.subject_id
                        inner join ei_subject_state ss on ss.id=s.subject_state_id ");
         
    } 
}
 
 