import React from 'react'
import { Calendar, TrendingUp, Target, Users, DollarSign, BarChart3 } from 'lucide-react'

function App() {
  return (
    <div className="min-h-screen bg-gray-50">
      {/* Navigation */}
      <nav className="bg-white shadow-lg border-b">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between h-16">
            <div className="flex items-center">
              <h1 className="text-xl font-bold text-gray-900">Modern ERP Payment System</h1>
            </div>
            <div className="flex items-center space-x-4">
              <span className="text-sm text-gray-700">Welcome, Admin</span>
              <span className="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                System Administrator
              </span>
            </div>
          </div>
        </div>
      </nav>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Header */}
        <div className="mb-8">
          <h2 className="text-3xl font-bold text-gray-900 mb-2">ERP Dashboard</h2>
          <p className="text-gray-600">Comprehensive budget and performance management system</p>
        </div>

        {/* Quick Stats */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div className="bg-white rounded-lg shadow-md p-6">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-blue-100 text-blue-600">
                <DollarSign className="w-6 h-6" />
              </div>
              <div className="ml-4">
                <p className="text-sm font-medium text-gray-500">Annual Budget</p>
                <p className="text-2xl font-bold text-gray-900">₦888.4M</p>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow-md p-6">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-green-100 text-green-600">
                <TrendingUp className="w-6 h-6" />
              </div>
              <div className="ml-4">
                <p className="text-sm font-medium text-gray-500">Total Achieved</p>
                <p className="text-2xl font-bold text-gray-900">₦773.3M</p>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow-md p-6">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-red-100 text-red-600">
                <BarChart3 className="w-6 h-6" />
              </div>
              <div className="ml-4">
                <p className="text-sm font-medium text-gray-500">Performance</p>
                <p className="text-2xl font-bold text-red-600">87.04%</p>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow-md p-6">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-purple-100 text-purple-600">
                <Users className="w-6 h-6" />
              </div>
              <div className="ml-4">
                <p className="text-sm font-medium text-gray-500">Active Officers</p>
                <p className="text-2xl font-bold text-gray-900">24</p>
              </div>
            </div>
          </div>
        </div>

        {/* Main Features */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          {/* Budget Management */}
          <div className="bg-white rounded-lg shadow-md p-6">
            <div className="flex items-center mb-4">
              <Calendar className="w-8 h-8 text-blue-600 mr-3" />
              <h3 className="text-lg font-semibold text-gray-900">Budget Management</h3>
            </div>
            <p className="text-gray-600 mb-4">
              Comprehensive annual budget planning and performance tracking across all income lines.
            </p>
            <div className="space-y-3">
              <a href="/budget_management.php" 
                 className="block w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-center transition-colors">
                Manage Budgets
              </a>
              <a href="/annual_budget_performance.php" 
                 className="block w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-center transition-colors">
                Annual Performance Summary
              </a>
            </div>
          </div>

          {/* Officer Performance */}
          <div className="bg-white rounded-lg shadow-md p-6">
            <div className="flex items-center mb-4">
              <Target className="w-8 h-8 text-green-600 mr-3" />
              <h3 className="text-lg font-semibold text-gray-900">Officer Performance</h3>
            </div>
            <p className="text-gray-600 mb-4">
              Track officer targets, achievements, and performance metrics with detailed analytics.
            </p>
            <div className="space-y-3">
              <a href="/officer_target_management.php" 
                 className="block w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-center transition-colors">
                Manage Targets
              </a>
              <a href="/mpr_income_lines_officers.php" 
                 className="block w-full px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 text-center transition-colors">
                Officer Analysis
              </a>
            </div>
          </div>
        </div>

        {/* System Features */}
        <div className="bg-white rounded-lg shadow-md p-6">
          <h3 className="text-lg font-semibold text-gray-900 mb-4">System Features</h3>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="/index.php" 
               className="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
              <DollarSign className="w-5 h-5 mr-2" />
              Payment Processing
            </a>
            
            <a href="/view_transactions.php" 
               className="flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
              <BarChart3 className="w-5 h-5 mr-2" />
              Transaction Management
            </a>
            
            <a href="/ledger.php" 
               className="flex items-center justify-center px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
              <Calendar className="w-5 h-5 mr-2" />
              General Ledger
            </a>
            
            <a href="/mpr.php" 
               className="flex items-center justify-center px-4 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
              <TrendingUp className="w-5 h-5 mr-2" />
              Monthly Reports
            </a>
          </div>
        </div>
      </div>
    </div>
  )
}

export default App