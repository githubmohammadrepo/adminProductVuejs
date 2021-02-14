
SELECT  finalCategory.*
       ,COUNT(pish_hikashop_product.product_id)
FROM(
	SELECT  new.*
	       ,pish_hikashop_category.category_name AS category_parent_name
	FROM 
	(
		SELECT  *
		FROM `pish_hikashop_category`
		WHERE category_type = 'product' 
		AND category_parent_id = 2
		ORDER BY `pish_hikashop_category`.`category_id` ASC 
	) AS new
	LEFT JOIN pish_hikashop_category
	ON new.category_parent_id = pish_hikashop_category.category_id
)as finalCategory
LEFT JOIN pish_hikashop_product
ON finalCategory.category_id = pish_hikashop_product.product_parent_id
GROUP BY finalCategory.category_id
