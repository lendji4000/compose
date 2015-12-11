<div class="row">
    <?php $token_api_user = $sf_user->getGuardUser()->getEiUser()->getTokenApi(); ?>
    <?php if ($sf_user->isAuthenticated()): ?>
        <div class="col-lg-12">
            <div class="panel panel-default eiPanel">
                <div class="panel-heading">
                    <h2><?php echo ei_icon('ei_edit') ?> API KEY </h2>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <div class="controls">
                                <div class="input-group">
                                    <input id="input_token_api" size="16" class="appendedInputButtons form-control" placeholder="API Token" type="text">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" id="button_token_api_show" type="button" title="Show API Key into input box">
                                        <?php echo ei_icon('ei_show','lg') ?>
                                    </button>
                                    <button class="btn btn-default" id="button_token_api_generate" type="button" title="Generate a new API Key"
                                            data-title="Confirmation"
                                            data-confirm="Are you sure you want to regenerate your access token ?">
                                        <i class="fa fa-refresh fa-lg"></i>
                                    </button>
                                </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <input type="hidden" value="<?php echo url_for1("@api_generate_token_api") ?>" name="url_to_generate_api_key" />
                <input type="hidden" value="<?php echo url_for1("@api_get_token_api") ?>" name="url_to_get_api_key" />
            </div>
        </div><!--/col-->

    <?php endif; ?>
</div><!--/row-->