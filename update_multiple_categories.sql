    -- Migration: Add support for multiple categories per article

-- Step 1: Create article_categories linking table
CREATE TABLE IF NOT EXISTS article_categories (
    article_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (article_id, category_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Step 2: Migrate existing data from articles.category_id to article_categories
INSERT INTO article_categories (article_id, category_id)
SELECT id, category_id FROM articles WHERE category_id IS NOT NULL;

-- Step 3: Remove the old category_id column (optional - keep it for backward compatibility)
-- ALTER TABLE articles DROP FOREIGN KEY articles_ibfk_1;
-- ALTER TABLE articles DROP COLUMN category_id;

-- Note: We're keeping category_id for now as the "primary" category for backward compatibility
