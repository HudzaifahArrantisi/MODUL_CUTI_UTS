

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nik VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    tempat_lahir VARCHAR(50) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    no_telp VARCHAR(15) NOT NULL,
    alamat TEXT NOT NULL,
    status_aktif TINYINT(1) DEFAULT 1,
    role ENUM('admin', 'user') NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cuti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tanggal_pengajuan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    jenis_cuti VARCHAR(50) NOT NULL,
    alasan TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    catatan_admin TEXT,a
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO users (nik, nama, jenis_kelamin, tempat_lahir, tanggal_lahir, no_telp, alamat, status_aktif, role, password) VALUES 
('0110224237', 'Admin 1', 'L', 'Jakarta', '2004-05-06', '085219545503', 'Jl. Kebagusan 3', 1, 'admin', 'admin123'),
('0110224005', 'Admin 2', 'L', 'Jakarta', '2004-10-25', '087845714215', 'Jl. Bougenville', 1, 'admin', 'admin1234'),
('0110224239', 'User 1', 'L', 'Jakarta', '2002-09-27', '0895359970617', 'Jl. Flamboyan', 1, 'user', 'user123'),
('0110224197', 'User 2', 'P', 'Sape', '2005-03-07', '085337456890', 'Jl. Arus', 1, 'user', 'user1234');

    UPDATE users SET password = 
    
    CASE 
        WHEN nik = '0110224237' THEN 'admin123' 
        WHEN nik = '0110224005' THEN 'admin1234'  
        WHEN nik = '0110224239' THEN 'user123'  
        WHEN nik = '0110224197' THEN 'user1234' 
    END;
