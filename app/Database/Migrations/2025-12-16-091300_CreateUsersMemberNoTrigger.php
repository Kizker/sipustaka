<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersMemberNoTrigger extends Migration
{
    public function up()
    {
        // sama seperti dump: trg_users_member_no AFTER INSERT -> update member_no format AGT-YYYY-000001
        // :contentReference[oaicite:8]{index=8}
        $sql = "
            CREATE TRIGGER trg_users_member_no
            AFTER INSERT ON users
            FOR EACH ROW
            UPDATE users
            SET member_no = CONCAT('AGT-', DATE_FORMAT(NOW(), '%Y'), '-', LPAD(NEW.id, 6, '0'))
            WHERE id = NEW.id
        ";
        $this->db->query($sql);
    }

    public function down()
    {
        $this->db->query("DROP TRIGGER IF EXISTS trg_users_member_no");
    }
}
