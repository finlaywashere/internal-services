<?php

$permission_map = ["authentication" => 0, "authentication/admin" => 100, "authentication/events" => 2,
"documents" => 0, "documents/create" => 1,
"main" => 0,
"inventory" => 0, "inventory/admin" => 100,
"inventory/cash" => 20, "inventory/cash/admin" => 100,
"inventory/customer/create" => 4, "inventory/customer" => 1,
"inventory/damaged" => 3,
"inventory/invoice/create" => 2, "inventory/invoice" => 1,
"inventory/journal" => 1,
"inventory/account" => 1, "inventory/account/admin" => 100, "inventory/account/move" => 4,
"inventory/product" => 1, "inventory/product/create" => 4, "inventory/product/update" => 2,
"inventory/reports/create" => 2];

?>
