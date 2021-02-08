-- get all brands
SELECT  *
FROM `pish_hikashop_category`
WHERE `user_id` IS NULL 
AND `category_type` = 'manufacturer' 
AND `category_name` != 'manufacturer'
ORDER BY `category_name` ASC