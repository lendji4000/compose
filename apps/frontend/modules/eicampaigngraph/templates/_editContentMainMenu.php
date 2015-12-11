<!-- Menu principal lors de l'edition du contenu des steps d'une campagne (Campaigns , Scenarios ) -->
 <ul class="nav nav-pills" id="editContentMainMenu">
    <li class=" col-lg-10 col-md-10 <?php echo((isset($activeItem) && $activeItem=='Campaigns')? 'active': '') ?>">
        <a href="#tabStepCampaigns" data-toggle="tab" title="Campaigns" id="eiCampaignsStepsOpenCampaignList">
            <?php echo ei_icon('ei_campaign', 'lg') ?> 
            <!--Campaigns-->
        </a>
    </li>
    <li class="col-lg-10 col-md-10 <?php echo((isset($activeItem) && $activeItem=='Scenarios')? 'active': '') ?>" >
        <a href="#tabStepScenarios" data-toggle="tab" title="Scenarios" id="eiCampaignsStepsOpenScenarioTree">
             <?php echo ei_icon('ei_scenario','lg') ?>
            <!--Scenarios-->
        </a>
    </li>
    <li class="col-lg-10 col-md-10 <?php echo((isset($activeItem) && $activeItem=='Functions')? 'active': '') ?>" >
        <a href="#tabStepFunctions" id='tabStepFunctionsLink' data-toggle="tab" title="Functions"> 
            <?php echo ei_icon('ei_function', 'lg') ?>
            <!--Functions-->
        </a>
    </li>
</ul>