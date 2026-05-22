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
