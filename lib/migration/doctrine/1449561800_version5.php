<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version5 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('ei_project_param', 'param_type', 'enum', '', array(
             'values' => 
             array(
              0 => 'IN',
              1 => 'OUT',
              2 => 'SONDE',
             ),
             'notnull' => '1',
             'default' => 'IN',
             ));
        $this->addColumn('ei_project_param', 'param_visibility', 'boolean', '25', array(
             'notnull' => '1',
             'default' => '1',
             ));
        $this->addColumn('ei_project_param', 'ei_table_name', 'string', '255', array(
             ));
        $this->addColumn('ei_project_param', 'ei_column_name', 'string', '255', array(
             ));
        $this->changeColumn('ei_project_param', 'name', 'string', '255', array(
             'notnull' => '1',
             ));
    }

    public function down()
    {
        $this->removeColumn('ei_project_param', 'param_type');
        $this->removeColumn('ei_project_param', 'param_visibility');
        $this->removeColumn('ei_project_param', 'ei_table_name');
        $this->removeColumn('ei_project_param', 'ei_column_name');
    }
}