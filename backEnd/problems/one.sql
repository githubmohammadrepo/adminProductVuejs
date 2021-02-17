SELECT pish_hikashop_category.category_id,
  pish_hikashop_category.category_name,
  pish_hikashop_category.user_id,
  pish_hikashop_file.file_path as brand_image,
  COUNT(pish_hikashop_product.product_id) as productsCount
FROM pish_hikashop_category 
  LEFT JOIN pish_hikashop_file ON pish_hikashop_file.file_ref_id = pish_hikashop_category.category_id
  LEFT JOIN pish_hikashop_product ON pish_hikashop_product.product_manufacturer_id = pish_hikashop_category.category_id
WHERE pish_hikashop_category.category_type = 'manufacturer'
  AND pish_hikashop_category.category_parent_id = 10
GROUP BY pish_hikashop_category.category_id


