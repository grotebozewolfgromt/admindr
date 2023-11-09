<?php
echo 'jo';
?>
<div class="formsection">
        <div class="formsection-header"><?php echo transm($this->getModuleName(), 'section_details_name', 'Details'); ?></div>
        <div class="formsection-line">
            <div class="form-description" for=""><?php echo transm($this->getModuleName(), 'field_transactiontype_description', 'Transaction type'); ?></div>
            <?php echo $this->objSelTransactionsType->renderNode(); ?>
        </div>
</div> 