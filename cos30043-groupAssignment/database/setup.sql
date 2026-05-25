-- Select your assigned Mercury database before running this script.

CREATE TABLE IF NOT EXISTS MRS_Account (
    account_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS MRS_Review (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    tmdb_movie_id INT NOT NULL,

    -- update to reflect figma design
    rating_plot INT NOT NULL DEFAULT 0,
    rating_acting INT NOT NULL DEFAULT 0,
    rating_pacing INT NOT NULL DEFAULT 0,
    rating ENUM('Peak',  "So bad it\'s good", 'Mid at best', 'Trash') NOT NULL,
    rewatch_status ENUM('First time watch', 'Rewatch') NOT NULL,
    met_expectations ENUM('Yes', 'No') NOT NULL,

    review_title VARCHAR(255),
    review_text TEXT,
    contains_spoilers BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_review_account
        FOREIGN KEY (account_id)
        REFERENCES MRS_Account(account_id)
        ON DELETE CASCADE,

    CONSTRAINT unique_account_movie_review
        UNIQUE (account_id, tmdb_movie_id)
);

CREATE TABLE IF NOT EXISTS MRS_UserMovieList (
    user_movie_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    tmdb_movie_id INT NOT NULL,
    status ENUM('want_to_watch', 'watching', 'watched') NOT NULL DEFAULT 'want_to_watch',
    is_favourite BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_user_movie_account
        FOREIGN KEY (account_id)
        REFERENCES MRS_Account(account_id)
        ON DELETE CASCADE,

    CONSTRAINT unique_account_movie_list
        UNIQUE (account_id, tmdb_movie_id)
);

-- Demo account seed data for local/server testing.
-- Login password for this seeded account: Password123!
INSERT INTO MRS_Account (account_id, username, email, password_hash)
VALUES
    (1, 'demo_user', 'demo.user@example.com', '$2y$12$BLQ3LGG0RomNM0rWgW7WM.TbgN5hSDVPN6nNhy/fJvqEX21YO7Qd.')
ON DUPLICATE KEY UPDATE
    username = VALUES(username),
    email = VALUES(email);

INSERT INTO MRS_UserMovieList (account_id, tmdb_movie_id, status, is_favourite)
VALUES
    (1, 19404, 'watched', TRUE),
    (1, 693134, 'watched', TRUE),
    (1, 329865, 'watched', TRUE),
    (1, 244786, 'want_to_watch', FALSE),
    (1, 872585, 'watching', FALSE),
    (1, 129, 'watched', TRUE)
ON DUPLICATE KEY UPDATE
    status = VALUES(status),
    is_favourite = VALUES(is_favourite),
    updated_at = CURRENT_TIMESTAMP;

INSERT INTO MRS_Review (
    account_id,
    tmdb_movie_id,
    rating_plot,
    rating_acting,
    rating_pacing,
    rating,
    rewatch_status,
    met_expectations,
    review_title,
    review_text
)
VALUES
    (1, 19404, 5, 5, 4, 'Peak', 'Rewatch', 'Yes', 'A favourite', 'A beautifully made film that still holds up.'),
    (1, 693134, 4, 4, 4, 'Peak', 'First time watch', 'Yes', 'Huge scale', 'Strong visuals, sound, and world building.'),
    (1, 329865, 5, 5, 5, 'Peak', 'First time watch', 'Yes', 'Quiet and memorable', 'A thoughtful film with a strong emotional centre.')
ON DUPLICATE KEY UPDATE
    rating_plot = VALUES(rating_plot),
    rating_acting = VALUES(rating_acting),
    rating_pacing = VALUES(rating_pacing),
    rating = VALUES(rating),
    rewatch_status = VALUES(rewatch_status),
    met_expectations = VALUES(met_expectations),
    review_title = VALUES(review_title),
    review_text = VALUES(review_text),
    updated_at = CURRENT_TIMESTAMP;
