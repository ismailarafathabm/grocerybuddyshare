<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li>
                    <a href="#!/"><img src="assets/img/icons/dashboard.svg"
                            alt="img" /><span>
                            Dashboard</span>
                    </a>
                </li>

                <?php
                if (!$xaccess['salesnew'] && !$xaccess['salesview']) {
                } else {
                ?>
                    <li class="submenu">
                        <a href="javascript:void(0);"><img
                                src="assets/img/icons/sales1.svg" alt="img" /><span>
                                Sales</span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <?php
                            if ($xaccess['salesnew']) {
                            ?>
                                <li><a href="#!sales-entry">New</a></li>
                            <?php
                            }
                            if ($xaccess['salesview']) {
                            ?>

                                <li><a href="#!sales-view">View</a></li>
                            <?php } ?>
                            <?php 
                                if($sessionuser === "superadmin"){
                                    ?>
                                    <li><a href="#!sales-rpt">Sales Report</a></li>
                                    <?php
                                }
                            ?>
                        </ul>
                    </li>
                <?php }
                if (!$xaccess['purchasenew'] && !$xaccess['purchaseview']) {
                } else {
                ?>
                    <li class="submenu">
                        <a href="javascript:void(0);"><img
                                src="assets/img/icons/purchase1.svg" alt="img" /><span>
                                Stock Entry</span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <?php
                            if ($xaccess['purchasenew']) {
                            ?>
                                <li><a href="#!stock-entry">New</a></li>
                            <?php
                            }
                            if ($xaccess['purchaseedit']) {
                            ?>
                                <li><a href="#!stockentry-view">View</a></li>
                            <?php
                            }
                            ?>


                        </ul>
                    </li>
                <?php
                }
                ?>

                <?php
                if (!$xaccess['productview'] && !$xaccess['productnew']) {
                }
                else{
                ?>
                    <li class="submenu">
                        <a href="javascript:void(0);"><img
                                src="assets/img/icons/product.svg" alt="img" /><span>
                                Product</span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <?php 
                                if($xaccess['productview'])
                                {
?>
                                    <li><a href="#!product-list">Products</a></li>
                                <li><a href="#!producttype-list">Product Types</a></li>
                                <li><a href="#!brand-list">Brands</a></li>
                                <li><a href="#!unit-list">Units</a></li>
<?php
                                }
                            ?>
                            
                        </ul>
                    </li>
                <?php
                }
                ?>

                <?php 
                    if(!$xaccess['supplierview']){

                    }else{
                        ?>
                          <li>
                            <a href="#!supplier-list"><img src="assets/img/icons/users1.svg" alt="img" /><span>
                                    Suppliers</span>
                            </a>
                        </li>
                        <?php
                    }
                ?>
                <?php 
                    if(!$xaccess['customerview']){

                    }else{
?>
                         <li>
                            <a href="#!customer-list"><img src="assets/img/icons/users1.svg" alt="img" /><span>
                                    Customers</span>
                            </a>
                        </li>
<?php
                    }
                ?>
               <?php 
                    if($sessionuser === "superadmin"){
                        ?>
                         <li>
                            <a href="#!users-list"><img src="assets/img/icons/users1.svg" alt="img" /><span>
                                    Users</span>
                            </a>
                        </li>
                        <?php
                    }
               ?>
                <li>
                            <a href="#!changepassword"><img src="assets/img/icons/settings.svg" alt="img" /><span>
                                    Change Password</span>
                            </a>
                        </li>
            </ul>
        </div>
    </div>
</div>