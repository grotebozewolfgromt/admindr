<style>
    /* we do the stylesheet inline, because it only applies to this page */
    .transactionlines-grid-row
    {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px,1fr));
    }
    .formsection-line input[type=text]
    {
        width: 100%;
    }

    #template-transactionslines
    {
        display: none;
    }
</style>

<script>
    /* we do the javascript inline, because it only applies to this page */
    
    /**
     * function addTransactionsLine()
     * 
     * handles all the actions from the button 'add line'
     */
    function addTransactionsLine()
    {
        objParentNode = document.getElementsByClassName('transactionlines-grid')[0];

        //retrieve the template line + duplicate it
        objTemplateDiv = document.getElementById('template-transactionslines');
        objNewLine = objTemplateDiv.cloneNode(true);
        objNewLine.id = '';//we want to get rid of the id to make it visible
        
        //temp remove the button (otherwise the new line is added below button)
        objButtonAddLine = document.getElementById('transactions-button-add-line');
        objParentNode.removeChild(objButtonAddLine);

        //add new line node       
        objParentNode.appendChild(objNewLine);
        
        //add button again
        objParentNode.appendChild(objButtonAddLine);
    }
</script>

<form id="detailsave" name="detailsave" method="post" action="<?php echo getURLThisScript(); ?>">

    <!-- form obligatory fields -->
    <?php echo $objController->objHidFormSubmitted->renderHTMLNode(); //able to detect if form is submitted via FormGenerator ?>
    <?php echo $objController->objHidCSRFToken->renderHTMLNode(); //able to detect Cross Site Request Forgery via FormGenerator ?>


    <!-- the details section -->
    <div class="formsection">
        <div class="formsection-header"><?php echo transm($objController->getModule(), 'detailsave_transactions_section_details_name', 'Details'); ?></div>

        <!-- invoice type -->
        <div class="formsection-line">
            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_transactiontype_description', 'Transaction type'); ?></div>
            <?php echo $objController->objSelTransactionsType->renderHTMLNode(); ?>
        </div>

        <!-- currency -->
        <div class="formsection-line">
            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_currency_description', 'Currency'); ?></div>
            <?php echo $objController->objSelCurrency->renderHTMLNode(); ?>
        </div>       

        <!-- buyer -->
        <div class="formsection-line">
            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_buyer_description', 'Buyer'); ?></div>
            <?php echo $objController->objHidBuyer->renderHTMLNode(); ?>
        </div>         
        
        <!-- purchase order number -->
        <div class="formsection-line">
            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_purchaseorderno_description', 'Purchase order number'); ?></div>
            <?php echo $objController->objEdtPurchaseOrderNo->renderHTMLNode(); ?>
        </div>          

    </div> 


    <!-- the lines section -->
    <div class="formsection">
        <div class="formsection-header"><?php echo transm($objController->getModule(), 'detailsave_transactions_section_lines_name', 'Lines'); ?></div>

        <div class="transactionlines-grid">

            <!-- this is the template we copy with javascript to make a new line -->
            <div class="formsection-line transactionlines-grid-row" id="template-transactionslines">
                <!-- quantity -->
                <div>
                    <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_quantity_description', 'Quantity'); ?></div>
                    <?php echo $objController->objEdtQuantity->renderHTMLNode(); ?>
                </div>

                <!-- description -->
                <div>
                    <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_description_description', 'Description'); ?></div>                    
                    <?php echo $objController->objEdtDescription->renderHTMLNode(); ?>
                </div>

                <!-- vat percentage -->
                <div>
                    <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_vatpercentage_description', 'VAT Percentage'); ?></div>                                        
                    <?php echo $objController->objEdtVATPercentage->renderHTMLNode(); ?>
                </div>                

                <!-- purchase price -->
                <div>
                    <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_purchasepriceexclvat_description', 'Purchase price (excl VAT)'); ?></div>                                        
                    <?php echo $objController->objEdtPurchasePriceExclVAT->renderHTMLNode(); ?>
                </div>   
                
                <!-- discount price -->
                <div>
                    <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_discountprice_description', 'Discount (excl VAT)'); ?></div>                                        
                    <?php echo $objController->objEdtDiscountPriceExclVAT->renderHTMLNode(); ?>
                </div>               
                
                <!-- unit price excluding vat -->
                <div>
                    <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_unitpriceexclvat_description', 'Unit price (excl VAT)'); ?></div>                                        
                    <?php echo $objController->objEdtPriceExclVAT->renderHTMLNode(); ?>
                </div>                   
            </div>

            <?php 
                $objController->objTransactionLines->resetRecordPointer();
                while($objController->objTransactionLines->next())
                {
                    $objController->objEdtQuantity->setValue($objController->objTransactionLines->getQuantity()->getValue());
                    $objController->objEdtDescription->setValue($objController->objTransactionLines->getDescription());
                    $objController->objEdtVATPercentage->setValue($objController->objTransactionLines->getVATPercentage()->getValue());
                    $objController->objEdtPurchasePriceExclVAT->setValue($objController->objTransactionLines->getUnitPurchasePriceExclVAT()->getValue());
                    $objController->objEdtDiscountPriceExclVAT->setValue($objController->objTransactionLines->getUnitDiscountExclVat()->getValue());
                    $objController->objEdtPriceExclVAT->setValue($objController->objTransactionLines->getUnitPurchasePriceExclVAT()->getValue());
                    ?>

                    <div class="formsection-line transactionlines-grid-row">
                        <!-- quantity -->
                        <div>
                            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_quantity_description', 'Quantity'); ?></div>
                            <?php echo $objController->objEdtQuantity->renderHTMLNode(); ?>
                        </div>

                        <!-- description -->
                        <div>
                            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_description_description', 'Description'); ?></div>                    
                            <?php echo $objController->objEdtDescription->renderHTMLNode(); ?>
                        </div>

                        <!-- vat percentage -->
                        <div>
                            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_vatpercentage_description', 'VAT Percentage'); ?></div>                                        
                            <?php echo $objController->objEdtVATPercentage->renderHTMLNode(); ?>
                        </div>                

                        <!-- purchase price -->
                        <div>
                            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_purchasepriceexclvat_description', 'Purchase price (excl VAT)'); ?></div>                                        
                            <?php echo $objController->objEdtPurchasePriceExclVAT->renderHTMLNode(); ?>
                        </div>   
                        
                        <!-- discount price -->
                        <div>
                            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_discountprice_description', 'Discount (excl VAT)'); ?></div>                                        
                            <?php echo $objController->objEdtDiscountPriceExclVAT->renderHTMLNode(); ?>
                        </div>               
                        
                        <!-- unit price excluding vat -->
                        <div>
                            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_unitpriceexclvat_description', 'Unit price (excl VAT)'); ?></div>                                        
                            <?php echo $objController->objEdtPriceExclVAT->renderHTMLNode(); ?>
                        </div>                   
                    </div>

                    <?php
                }
            ?>

            <!-- add line button -->
            <input type="button" onclick="addTransactionsLine()" value="<?php echo transm($objController->getModule(), 'detailsave_transactions_button_addline', 'Add line +'); ?>" class="button_normal" id="transactions-button-add-line">
        </div><!-- END transactionlines-grid -->

    </div><!-- END formsection --> 

    
    <!-- notes section -->
    <div class="formsection">
        <div class="formsection-header"><?php echo transm($objController->getModule(), 'detailsave_transactions_section_notes_name', 'Notes'); ?></div>


        <!-- internal notes -->
        <div class="formsection-line">
            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_internalnotes_description', 'Internal notes - only visible to you'); ?></div>
            <?php echo $objController->objTxtNotesInternal->renderHTMLNode(); ?>
        </div>       

        <!-- external notes -->
        <div class="formsection-line">
            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_externalnotes_description', 'External notes - shown on invoice'); ?></div>
            <?php echo $objController->objTxtNotesExternal->renderHTMLNode(); ?>
        </div>        
    </div> 



    <!-- history section -->
    <div class="formsection">
        <div class="formsection-header"><?php echo transm($objController->getModule(), 'detailsave_transactions_section_history_name', 'History'); ?></div>

    </div> 


    <!-- command panel -->
    <div class="formsection div_commandpanel">
        <?php echo $objController->objSubmit->renderHTMLNode(); ?>
        <?php echo $objController->objCancel->renderHTMLNode(); ?>
    </div> 

</form>
