PRAGMA foreign_keys = ON;

BEGIN TRANSACTION;

-- --------------------------------------------------
-- Minimal required external tables
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS USER (
                                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                                    name TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS AERIES_GOODS_ENTITY (
                                                   id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                   name TEXT NOT NULL
);

-- --------------------------------------------------
-- Filament
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS AERIES_FILAMENT_BRAND (
                                                     id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                     name TEXT NOT NULL UNIQUE,
                                                     created_at TEXT NOT NULL,
                                                     updated_at TEXT
);

CREATE TABLE IF NOT EXISTS AERIES_FILAMENT_TYPE (
                                                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                    name TEXT NOT NULL UNIQUE,
                                                    created_at TEXT NOT NULL,
                                                    updated_at TEXT
);

CREATE TABLE IF NOT EXISTS AERIES_FILAMENT_COLOR (
                                                     id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                     name TEXT NOT NULL,
                                                     hex_color TEXT,
                                                     created_at TEXT NOT NULL,
                                                     updated_at TEXT
);

CREATE TABLE IF NOT EXISTS AERIES_FILAMENT (
                                               id INTEGER PRIMARY KEY AUTOINCREMENT,
                                               aeries_filament_brand_id INTEGER NOT NULL,
                                               aeries_filament_type_id INTEGER NOT NULL,
                                               aeries_filament_color_id INTEGER NOT NULL,
                                               created_at TEXT NOT NULL,
                                               updated_at TEXT,
                                               FOREIGN KEY (aeries_filament_brand_id) REFERENCES AERIES_FILAMENT_BRAND(id),
    FOREIGN KEY (aeries_filament_type_id) REFERENCES AERIES_FILAMENT_TYPE(id),
    FOREIGN KEY (aeries_filament_color_id) REFERENCES AERIES_FILAMENT_COLOR(id)
    );

-- --------------------------------------------------
-- Machine Models & Machines
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS AERIES_PRINT_MACHINE_MODEL (
                                                          id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                          name TEXT NOT NULL,
                                                          manufacturer TEXT,
                                                          build_volume_x_mm REAL,
                                                          build_volume_y_mm REAL,
                                                          build_volume_z_mm REAL,
                                                          created_at TEXT NOT NULL,
                                                          updated_at TEXT,
                                                          deleted_at TEXT
);

CREATE TABLE IF NOT EXISTS AERIES_PRINT_MACHINE (
                                                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                    aeries_print_machine_model_id INTEGER NOT NULL,
                                                    name TEXT NOT NULL,
                                                    status TEXT NOT NULL CHECK(status IN ('idle','printing','maintenance','offline')),
    in_service_at TEXT,
    last_maintenance_at TEXT,
    created_at TEXT NOT NULL,
    updated_at TEXT,
    deleted_at TEXT,
    FOREIGN KEY (aeries_print_machine_model_id) REFERENCES AERIES_PRINT_MACHINE_MODEL(id)
    );

-- --------------------------------------------------
-- Print Profile
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS AERIES_PRINT_PROFILE (
                                                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                    aeries_goods_entity_id INTEGER NOT NULL,
                                                    aeries_print_machine_model_id INTEGER NOT NULL,
                                                    version INTEGER NOT NULL,
                                                    is_active INTEGER NOT NULL DEFAULT 1,
                                                    parent_aeries_print_profile_id INTEGER,
                                                    name TEXT NOT NULL,
                                                    file_path TEXT NOT NULL,
                                                    file_hash TEXT NOT NULL,
                                                    output_quantity INTEGER NOT NULL,
                                                    layer_height_mm REAL,
                                                    infill_percentage REAL,
                                                    supports_enabled INTEGER NOT NULL DEFAULT 0,
                                                    created_by_user_id INTEGER NOT NULL,
                                                    created_at TEXT NOT NULL,
                                                    updated_at TEXT,
                                                    deleted_at TEXT,
                                                    FOREIGN KEY (aeries_goods_entity_id) REFERENCES AERIES_GOODS_ENTITY(id),
    FOREIGN KEY (aeries_print_machine_model_id) REFERENCES AERIES_PRINT_MACHINE_MODEL(id),
    FOREIGN KEY (parent_aeries_print_profile_id) REFERENCES AERIES_PRINT_PROFILE(id),
    FOREIGN KEY (created_by_user_id) REFERENCES USER(id)
    );

CREATE UNIQUE INDEX IF NOT EXISTS idx_profile_version
    ON AERIES_PRINT_PROFILE(aeries_goods_entity_id, version);

-- --------------------------------------------------
-- Profile Plates
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS AERIES_PRINT_PROFILE_PLATE (
                                                          id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                          aeries_print_profile_id INTEGER NOT NULL,
                                                          plate_name TEXT,
                                                          plate_number INTEGER NOT NULL,
                                                          estimated_print_time_minutes INTEGER,
                                                          notes TEXT,
                                                          created_at TEXT NOT NULL,
                                                          updated_at TEXT,
                                                          deleted_at TEXT,
                                                          FOREIGN KEY (aeries_print_profile_id) REFERENCES AERIES_PRINT_PROFILE(id),
    UNIQUE(aeries_print_profile_id, plate_number)
    );

CREATE TABLE IF NOT EXISTS AERIES_PRINT_PROFILE_PLATE_FILAMENT (
                                                                   id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                                   aeries_print_profile_plate_id INTEGER NOT NULL,
                                                                   aeries_filament_id INTEGER NOT NULL,
                                                                   estimated_weight_grams REAL,
                                                                   created_at TEXT NOT NULL,
                                                                   updated_at TEXT,
                                                                   deleted_at TEXT,
                                                                   FOREIGN KEY (aeries_print_profile_plate_id) REFERENCES AERIES_PRINT_PROFILE_PLATE(id),
    FOREIGN KEY (aeries_filament_id) REFERENCES AERIES_FILAMENT(id)
    );

-- --------------------------------------------------
-- Print Job
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS AERIES_PRINT_JOB (
                                                id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                aeries_print_profile_id INTEGER NOT NULL,
                                                run_quantity INTEGER NOT NULL,
                                                type TEXT NOT NULL CHECK(type IN ('request','stock_refill','testing','decor','Personal')),
    status TEXT NOT NULL CHECK(status IN ('draft','queued','printing','paused','completed','failed','canceled')),
    notes TEXT,
    scheduled_at TEXT,
    started_at TEXT,
    finished_at TEXT,
    created_by_user_id INTEGER NOT NULL,
    created_at TEXT NOT NULL,
    updated_at TEXT,
    deleted_at TEXT,
    FOREIGN KEY (aeries_print_profile_id) REFERENCES AERIES_PRINT_PROFILE(id),
    FOREIGN KEY (created_by_user_id) REFERENCES USER(id)
    );

CREATE INDEX IF NOT EXISTS idx_print_job_status
    ON AERIES_PRINT_JOB(status);

-- --------------------------------------------------
-- Print Plate Job
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS AERIES_PRINT_PLATE_JOB (
                                                      id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                      aeries_print_job_id INTEGER NOT NULL,
                                                      aeries_print_profile_plate_id INTEGER NOT NULL,
                                                      status TEXT NOT NULL CHECK(status IN ('draft','queued','printing','paused','completed','failed','canceled')),
    started_at TEXT,
    finished_at TEXT,
    created_at TEXT NOT NULL,
    updated_at TEXT,
    deleted_at TEXT,
    FOREIGN KEY (aeries_print_job_id) REFERENCES AERIES_PRINT_JOB(id),
    FOREIGN KEY (aeries_print_profile_plate_id) REFERENCES AERIES_PRINT_PROFILE_PLATE(id)
    );

-- --------------------------------------------------
-- Print Plate Job Result
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS AERIES_PRINT_PLATE_JOB_RESULT (
                                                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                            aeries_print_plate_job_id INTEGER NOT NULL,
                                                            result TEXT NOT NULL CHECK(result IN ('success','failed','canceled')),
    failure_reason TEXT,
    quantity_completed INTEGER,
    quantity_failed INTEGER,
    material_wasted_grams REAL,
    notes TEXT,
    created_at TEXT NOT NULL,
    updated_at TEXT,
    FOREIGN KEY (aeries_print_plate_job_id) REFERENCES AERIES_PRINT_PLATE_JOB(id)
    );

-- --------------------------------------------------
-- Job Status History
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS AERIES_PRINT_JOB_STATUS_HISTORY (
                                                               id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                               aeries_print_job_id INTEGER NOT NULL,
                                                               old_status TEXT CHECK(old_status IN ('draft','queued','printing','paused','completed','failed','canceled')),
    new_status TEXT NOT NULL CHECK(new_status IN ('draft','queued','printing','paused','completed','failed','canceled')),
    new_status_notes TEXT,
    comment TEXT,
    changed_by_user_id INTEGER NOT NULL,
    changed_at TEXT NOT NULL,
    created_at TEXT NOT NULL,
    updated_at TEXT,
    FOREIGN KEY (aeries_print_job_id) REFERENCES AERIES_PRINT_JOB(id),
    FOREIGN KEY (changed_by_user_id) REFERENCES USER(id)
    );

-- --------------------------------------------------
-- Plate Job Status History
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS AERIES_PRINT_PLATE_JOB_STATUS_HISTORY (
                                                                     id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                                     aeries_print_plate_job_id INTEGER NOT NULL,
                                                                     old_status TEXT CHECK(old_status IN ('draft','queued','printing','paused','completed','failed','canceled')),
    new_status TEXT NOT NULL CHECK(new_status IN ('draft','queued','printing','paused','completed','failed','canceled')),
    new_status_notes TEXT,
    comment TEXT,
    changed_by_user_id INTEGER NOT NULL,
    changed_at TEXT NOT NULL,
    created_at TEXT NOT NULL,
    updated_at TEXT,
    deleted_at TEXT,
    FOREIGN KEY (aeries_print_plate_job_id) REFERENCES AERIES_PRINT_PLATE_JOB(id),
    FOREIGN KEY (changed_by_user_id) REFERENCES USER(id)
    );

-- --------------------------------------------------
-- Plate Job Uses Machine
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS AERIES_PRINT_PLATE_JOB_USES_PRINT_MACHINE (
                                                                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                                        aeries_print_plate_job_id INTEGER NOT NULL,
                                                                        aeries_print_machine_id INTEGER NOT NULL,
                                                                        status TEXT NOT NULL CHECK(status IN ('assigned','printing','completed','canceled')),
    started_at TEXT,
    finished_at TEXT,
    created_at TEXT NOT NULL,
    updated_at TEXT,
    deleted_at TEXT,
    FOREIGN KEY (aeries_print_plate_job_id) REFERENCES AERIES_PRINT_PLATE_JOB(id),
    FOREIGN KEY (aeries_print_machine_id) REFERENCES AERIES_PRINT_MACHINE(id)
    );

CREATE INDEX IF NOT EXISTS idx_plate_job_machine_machine
    ON AERIES_PRINT_PLATE_JOB_USES_PRINT_MACHINE(aeries_print_machine_id);

-- --------------------------------------------------
-- Print Plate Queue
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS AERIES_PRINT_PLATE_QUEUE (
                                                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                        aeries_print_plate_job_id INTEGER NOT NULL UNIQUE,
                                                        status TEXT NOT NULL CHECK(status IN ('waiting','printing','paused','completed','removed')),
    queued_at TEXT NOT NULL,
    created_at TEXT NOT NULL,
    updated_at TEXT,
    deleted_at TEXT,
    FOREIGN KEY (aeries_print_plate_job_id) REFERENCES AERIES_PRINT_PLATE_JOB(id)
    );

CREATE INDEX IF NOT EXISTS idx_plate_queue_status
    ON AERIES_PRINT_PLATE_QUEUE(status);

-- --------------------------------------------------
-- Plate Queue Status History
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS AERIES_PRINT_PLATE_QUEUE_STATUS_HISTORY (
                                                                      id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                                      aeries_print_plate_queue_id INTEGER NOT NULL,
                                                                      aeries_print_plate_job_id INTEGER NOT NULL,
                                                                      new_status TEXT NOT NULL CHECK(new_status IN ('waiting','printing','paused','completed','removed')),
    old_status TEXT NOT NULL CHECK(old_status IN ('waiting','printing','paused','completed','removed')),
    queued_at TEXT,
    created_at TEXT NOT NULL,
    updated_at TEXT,
    deleted_at TEXT,
    FOREIGN KEY (aeries_print_plate_queue_id) REFERENCES AERIES_PRINT_PLATE_QUEUE(id),
    FOREIGN KEY (aeries_print_plate_job_id) REFERENCES AERIES_PRINT_PLATE_JOB(id)
    );

COMMIT;
