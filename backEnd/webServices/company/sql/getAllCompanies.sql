SELECT  New.*
       ,pish_hikashop_category.category_id
       ,pish_hikashop_category.user_id AS category_user_id
       ,pish_hikashop_category.category_name
       ,pish_hikashop_file.file_ref_id
       ,pish_hikashop_file.file_path
FROM 
(
	SELECT  pish_phocamaps_marker_company.id
	       ,pish_phocamaps_marker_company.user_id
	       ,pish_phocamaps_marker_company.ShopName
	       ,pish_phocamaps_marker_company.ManagerName
	       ,pish_phocamaps_marker_company.phone
	       ,pish_phocamaps_marker_company.MobilePhone
	       ,pish_phocamaps_marker_company.Address
	FROM `pish_phocamaps_marker_company`
	ORDER BY pish_phocamaps_marker_company.id ASC
	LIMIT 0,10
) AS New
LEFT JOIN pish_hikashop_category
ON New.user_id =pish_hikashop_category.category_id
LEFT JOIN pish_hikashop_file
ON  pish_hikashop_category.category_id = pish_hikashop_file.file_ref_id
