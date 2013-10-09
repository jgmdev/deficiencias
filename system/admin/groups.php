<?php
/** 
 * @author Jefferson González
 * @license MIT
*/
exit;
?>

row: 0
	field: title
		<?=print t("Groups")?>
	field;

	field: content
		<?php
			protected_page(array("view_groups"));

			add_tab(t("Users"), "admin/users");
			add_tab(t("Create Group"), "admin/groups/add");
		?>

		<?php

			$groups = get_group_list();
            $groups["Guest"] = "guest";

			print "<table class=\"groups-list\">\n";

			print "<thead><tr>\n";

			print "<td>" . t("Name") . "</td>\n";
			print "<td>" . t("Description") . "</td>\n";
			print "<td>" . t("Operation") . "</td>\n";

			print  "</tr></thead>\n";

			foreach($groups as $name=>$machine_name)
			{
				$group_data = get_group_data($machine_name);
				$description = $group_data["description"];

				print "<tr>\n";

				print "<td>" . t($name) . "</td>\n";
				print "<td>" . t($description) . "</td>\n";

				$edit_url = print_url("admin/groups/edit",array("group"=>$machine_name));
				$permissions_url = print_url("admin/groups/permissions",array("group"=>$machine_name));
				$delete_url = print_url("admin/groups/delete", array("group"=>$machine_name));
				$edit_text = t("Edit");
				$permissions_text = t("Permissions");
				$delete_text = t("Delete");

				print "<td>
						<a href=\"$edit_url\">$edit_text</a>&nbsp;
						<a href=\"$permissions_url\">$permissions_text</a>&nbsp;
						<a href=\"$delete_url\">$delete_text</a>
					   </td>\n";

				print "</tr>\n";
			}

			print "</table>\n";
		?>
	field;
	
	field: is_system
		1
	field;
row;
