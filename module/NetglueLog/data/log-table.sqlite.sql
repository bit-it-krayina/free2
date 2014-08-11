CREATE TABLE 'ng_log' (
	'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	'timestamp' INTEGER,
	'priority_name' TEXT,
	'priority' INTEGER,
	'message' BLOB,
	'type' TEXT,
	'error_code' INTEGER,
	'exception_class' TEXT,
	'file_path' TEXT,
	'line' INTEGER,
	'called_function' TEXT,
	'trace' BLOB,
	'ip_address' TEXT,
	'request_id' TEXT,
	'extra' BLOB
);
CREATE INDEX 'timestamp' ON "ng_log" ("timestamp");
CREATE INDEX 'priority' ON "ng_log" ("priority");
