<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        echo "\n🚀 Starting Database Seeding Process...\n\n";
        echo "================================================\n";

        // PHASE 1: Core Data (MUST RUN FIRST)
        echo "\n📦 PHASE 1: Core Data\n";
        echo "------------------------------------------------\n";
        $this->call('RoleSeeder');
        $this->call('DivisiSeeder');
        $this->call('SettingSeeder');

        // PHASE 2: Users & Interns
        echo "\n👥 PHASE 2: Users & Interns\n";
        echo "------------------------------------------------\n";
        $this->call('UserSeeder');
        $this->call('InternSeeder');

        // PHASE 3: Attendance & Leaves
        echo "\n📅 PHASE 3: Attendance & Leave Data\n";
        echo "------------------------------------------------\n";
        // WARNING: Akan generate ~3000+ records, bisa memakan waktu 1-2 menit
        $this->call('AttendanceSeeder');

        // Leave/Cuti Data
        $this->call('LeaveSeeder');

        // PHASE 4: KPI Indicators
        echo "\n📈 PHASE 4: KPI System\n";
        echo "------------------------------------------------\n";
        $this->call('KPIIndicatorSeeder');

        // PHASE 5: Activities & Projects
        echo "\n📋 PHASE 5: Activities & Projects\n";
        echo "------------------------------------------------\n";
        $this->call('DailyActivitySeeder');
        $this->call('WeeklyProjectSeeder');

        // PHASE 6: Attendance Corrections
        echo "\n✏️  PHASE 6: Attendance Corrections\n";
        echo "------------------------------------------------\n";
        $this->call('AttendanceCorrectionSeeder');

        // PHASE 7: KPI Assessments & Results (depends on assessments, activities, projects)
        echo "\n🏆 PHASE 7: KPI Assessments & Results\n";
        echo "------------------------------------------------\n";
        $this->call('KpiAssessmentSeeder');
        $this->call('KpiMonthlyResultSeeder');
        $this->call('KpiPeriodResultSeeder');

        // PHASE 8: Allowance System (depends on attendance data)
        echo "\n💰 PHASE 8: Allowance System\n";
        echo "------------------------------------------------\n";
        $this->call('AllowancePeriodSeeder');
        $this->call('AllowanceSeeder');
        $this->call('AllowanceSlipSeeder');
        $this->call('UpdateBankInfoSeeder');

        // PHASE 9: Notifications (depends on all previous data)
        echo "\n🔔 PHASE 9: Notifications\n";
        echo "------------------------------------------------\n";
        $this->call('NotificationSeeder');

        // PHASE 10: Audit Logs (records of all system activity)
        echo "\n📝 PHASE 10: Audit Logs\n";
        echo "------------------------------------------------\n";
        $this->call('AuditLogSeeder');

        // Summary
        echo "\n================================================\n";
        echo "✅ Database Seeding Completed!\n\n";
        echo "📝 Summary:\n";
        echo "   - 5 Roles\n";
        echo "   - 8 Divisi\n";
        echo "   - 19 Settings\n";
        echo "   - 43 Users (8 staff + 35 interns)\n";
        echo "   - 35 Intern profiles\n";
        echo "   - ~4000+ Attendance records (6 months)\n";
        echo "   - Leave Requests (Cuti/Izin/Sakit)\n";
        echo "   - Attendance Corrections\n";
        echo "   - 8 KPI Indicators\n";
        echo "   - KPI Assessments (6 months × 35 interns × 8 indicators)\n";
        echo "   - KPI Monthly Results (6 months × 35 interns)\n";
        echo "   - KPI Period Results (35 interns with ranking)\n";
        echo "   - Daily Activities (3 months data)\n";
        echo "   - Weekly Projects (12 weeks data)\n";
        echo "   - 6 Allowance Periods (3 paid, 1 approved, 1 calculated, 1 draft)\n";
        echo "   - Allowance records for all interns per period\n";
        echo "   - Allowance Slips for paid allowances\n";
        echo "   - Notifications for all user roles\n";
        echo "   - Audit Logs across all modules\n\n";
        echo "🔐 Default Login Credentials:\n";
        echo "   Admin: admin@muamalatbank.com / password123\n";
        echo "   HR: hr@muamalatbank.com / password123\n";
        echo "   Finance: finance@muamalatbank.com / password123\n";
        echo "   Mentor IT: mentor.it@muamalatbank.com / password123\n";
        echo "   Intern 1: intern001@muamalatbank.com / password123\n\n";
    }
}
