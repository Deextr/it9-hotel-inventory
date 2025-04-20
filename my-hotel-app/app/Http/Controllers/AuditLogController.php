<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs with filtering options.
     */
    public function index(Request $request)
    {
        try {
            // Start building the query
            $query = DB::table('audit_logs')
                ->leftJoin('users', 'audit_logs.user_id', '=', 'users.id')
                ->select(
                    'audit_logs.*',
                    'users.name as user_name',
                    'users.email as user_email'
                );
            
            // Filter by user
            if ($request->filled('user_id')) {
                $query->where('audit_logs.user_id', $request->user_id);
            }
            
            // Filter by action
            if ($request->filled('action')) {
                $query->where('audit_logs.action', $request->action);
            }
            
            // Filter by table
            if ($request->filled('table_name')) {
                $query->where('audit_logs.table_name', $request->table_name);
            }
            
            // Filter by date range
            if ($request->filled('date_from')) {
                $dateFrom = Carbon::parse($request->date_from)->startOfDay();
                $query->where('audit_logs.created_at', '>=', $dateFrom);
            }
            
            if ($request->filled('date_to')) {
                $dateTo = Carbon::parse($request->date_to)->endOfDay();
                $query->where('audit_logs.created_at', '<=', $dateTo);
            }
            
            // Order by latest first
            $query->orderBy('audit_logs.created_at', 'desc');
            
            // Paginate with query string preservation
            $logs = $query->paginate(10)->withQueryString();
            
            // Get data for filters
            $users = User::orderBy('name')->get();
            $actions = DB::table('audit_logs')->distinct()->pluck('action');
            $tables = DB::table('audit_logs')->whereNotNull('table_name')->distinct()->pluck('table_name');
            
            Log::info('Audit logs retrieved: ' . $logs->total());
            
            return view('audit.index', compact('logs', 'users', 'actions', 'tables'));
            
        } catch (\Exception $e) {
            Log::error('Error loading audit logs: ' . $e->getMessage());
            return view('audit.index', [
                'logs' => collect([])->paginate(15),
                'users' => User::orderBy('name')->get(),
                'actions' => collect([]),
                'tables' => collect([]),
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Display details of a specific audit log entry.
     */
    public function show($id)
    {
        try {
            $auditLog = DB::table('audit_logs')
                ->leftJoin('users', 'audit_logs.user_id', '=', 'users.id')
                ->select(
                    'audit_logs.*',
                    'users.name as user_name',
                    'users.email as user_email'
                )
                ->where('audit_logs.id', $id)
                ->first();
                
            if (!$auditLog) {
                return redirect()->route('audit-logs.index')
                    ->with('error', 'Audit log not found');
            }
            
            // Parse JSON values
            $auditLog->old_values = json_decode($auditLog->old_values);
            $auditLog->new_values = json_decode($auditLog->new_values);
            
            return view('audit.show', compact('auditLog'));
        } catch (\Exception $e) {
            Log::error('Error viewing audit log details: ' . $e->getMessage());
            return redirect()->route('audit-logs.index')
                ->with('error', 'Error viewing audit log: ' . $e->getMessage());
        }
    }
} 