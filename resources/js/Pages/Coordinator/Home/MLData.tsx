import React, { ReactNode, useState } from "react";
import { Head, usePage, router } from "@inertiajs/react";
import { FileUp } from "lucide-react";
import { route } from "ziggy-js";

interface MLDataProps {
    totalPanel: number;
    totalProjectArea: number;
    totalProjectType: number;
    totalSamples: number;
    totalLabels: number;
    asgCountPerPanel: Record<string, number>;
}

interface Flash {
    error?: string;
    success?: string;
    warning?: string[][];
}

interface TableHeader {
    label: string;
    key: string;
}

export default function MLData() {
    const { props } = usePage<{
        totalPanel: number;
        totalProjectArea: number;
        totalProjectType: number;
        totalSamples: number;
        totalLabels: number;
        asgCountPerPanel: Record<string, number>;
        flash?: Flash;
    }>();

    const {
        totalPanel,
        totalProjectArea,
        totalProjectType,
        totalSamples,
        totalLabels,
        asgCountPerPanel,
        flash,
    } = props;

    // Convert panel assignment data for table
    // Convert panel assignment data for table, sort, and then assign IDs in the new order
    const panelAssignmentData = Object.entries(asgCountPerPanel)
        .map(([name, count]) => ({
            name,
            assignmentCount: count,
        }))
        .sort((a, b) => b.assignmentCount - a.assignmentCount)
        .map((item, index) => ({
            ...item,
            id: index + 1, // Assign ID after sorting
        }));

    // State for pagination and search
    const [rowsPerPage, setRowsPerPage] = useState(10);
    const [currentPage, setCurrentPage] = useState(1);
    const [searchQuery, setSearchQuery] = useState("");

    // Filter data based on search query
    const filteredData = panelAssignmentData.filter((row) => {
        return (
            row.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
            row.assignmentCount
                .toString()
                .toLowerCase()
                .includes(searchQuery.toLowerCase())
        );
    });

    // Calculate total pages after filtering
    const totalPages = Math.ceil(filteredData.length / rowsPerPage);

    // Paginate data
    const paginatedData = filteredData.slice(
        (currentPage - 1) * rowsPerPage,
        currentPage * rowsPerPage
    );

    const handleExport = () => {
        window.location.href = route("coordinator.export.ai-data");
    };

    return (
        <div className="min-h-screen bg-gray-100 flex w-full pb-6 border-b">
            <Head title="Machine Learning Data Overview" />

            {/* Main Content */}
            <main className="w-full">
                <div className="py-6 min-w-full">
                    <div className="max-w-7xl">

                        <div className="flex justify-between">

                        <h1 className="text-2xl font-semibold text-gray-900 ml-2">
                            Machine Learning Data Overview
                        </h1>

                        <button
                            type="button"
                            className="p-2 px-3 bg-blue-400 hover:bg-blue-500 transition text-white rounded ml-2 mr-4 font-semibold"
                            onClick={() => handleExport()}
                            title="Import Students"
                        >
                            <div className="flex">
                                <FileUp className="mr-2"/> 
                                Export ML Data
                            </div>
                        </button>

                        </div>
                        

                        {/* Flash Messages */}
                        {flash?.success && (
                            <div className="mt-4 p-4 bg-green-100 text-green-700 rounded-md">
                                {flash.success}
                            </div>
                        )}

                        {flash?.error && (
                            <div className="mt-4 p-4 bg-red-100 text-red-700 rounded-md">
                                {flash.error}
                            </div>
                        )}

                        {/* Stat Cards */}
                        <div className="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5 mx-2">
                            <Card>
                                <div className="p-5">
                                    <div className="text-sm font-medium text-gray-500 truncate">
                                        Total Panel
                                    </div>
                                    <div className="mt-1 text-3xl font-semibold text-gray-900">
                                        {totalPanel}
                                    </div>
                                </div>
                            </Card>

                            <Card>
                                <div className="p-5">
                                    <div className="text-sm font-medium text-gray-500 truncate">
                                        Project Areas
                                    </div>
                                    <div className="mt-1 text-3xl font-semibold text-gray-900">
                                        {totalProjectArea}
                                    </div>
                                </div>
                            </Card>

                            <Card>
                                <div className="p-5">
                                    <div className="text-sm font-medium text-gray-500 truncate">
                                        Project Types
                                    </div>
                                    <div className="mt-1 text-3xl font-semibold text-gray-900">
                                        {totalProjectType}
                                    </div>
                                </div>
                            </Card>

                            <Card>
                                <div className="p-5">
                                    <div className="text-sm font-medium text-gray-500 truncate">
                                        Total Samples
                                    </div>
                                    <div className="mt-1 text-3xl font-semibold text-gray-900">
                                        {totalSamples}
                                    </div>
                                </div>
                            </Card>

                            <Card>
                                <div className="p-5">
                                    <div className="text-sm font-medium text-gray-500 truncate">
                                        Total Labels
                                    </div>
                                    <div className="mt-1 text-3xl font-semibold text-gray-900">
                                        {totalLabels}
                                    </div>
                                </div>
                            </Card>
                        </div>

                        {/* Search Input */}
                        <div className="mt-6 flex justify-between mx-2">
                            <div className="flex ">
                                <label className="font-semibold">
                                    Rows per page:
                                    <select
                                        className="ml-2 border border-gray-300 rounded p-1 bg-white"
                                        value={rowsPerPage}
                                        onChange={(e) => {
                                            setRowsPerPage(
                                                Number(e.target.value)
                                            );
                                            setCurrentPage(1); // Reset to first page
                                        }}
                                    >
                                        <option value={5}>5</option>
                                        <option value={10}>10</option>
                                        <option value={20}>20</option>
                                        <option value={50}>50</option>
                                    </select>
                                </label>
                            </div>
                            <input
                                type="text"
                                className="border border-gray-300 rounded p-2 w-1/4 bg-white hover:border-[#6D2323]"
                                placeholder="Search Panel"
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                            />
                        </div>

                        {/* Panel Assignment Table */}
                        <div className="mt-2 w-full">
                            <h2 className="text-lg font-medium text-gray-900 ml-2">
                                Panel Assignment Distribution
                            </h2>
                            <div className="mt-4 shadow overflow-hidden border-gray-200 w-full">
                                <Table
                                    headers={[
                                        { label: "No.", key: "id" },
                                        { label: "Panel Name", key: "name" },
                                        {
                                            label: "Assignment Count",
                                            key: "assignmentCount",
                                        },
                                    ]}
                                    data={paginatedData}
                                />
                            </div>
                        </div>

                        {/* Pagination Controls */}
                        <div className="flex justify-center space-x-2 items-center mt-4">
                            <button
                                type="button"
                                className="px-3 py-1 bg-white rounded disabled:opacity-50 hover:bg-gray-100 transition border border-gray-300"
                                disabled={currentPage === 1}
                                onClick={() =>
                                    setCurrentPage((prev) => prev - 1)
                                }
                            >
                                Prev
                            </button>

                            <span>
                                Page {currentPage} of {totalPages}
                            </span>

                            <button
                                type="button"
                                className="px-3 py-1 bg-white rounded disabled:opacity-50 hover:bg-gray-100 transition border border-gray-300"
                                disabled={currentPage === totalPages}
                                onClick={() =>
                                    setCurrentPage((prev) => prev + 1)
                                }
                            >
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    );
}

// Card Component
function Card({
    children,
    className = "",
}: {
    children: ReactNode;
    className?: string;
}) {
    return (
        <div
            className={`bg-white overflow-hidden shadow rounded-lg w-full ${className}`}
        >
            {children}
        </div>
    );
}

// Table Component
function Table({
    headers,
    data,
    className = "w-full",
}: {
    headers: TableHeader[];
    data: Record<string, any>[];
    className?: string;
}) {
    return (
        <table className={`w-full divide-y divide-gray-200 ${className}`}>
            <thead className="bg-gray-200">
                <tr>
                    {headers.map((header) => (
                        <th
                            key={header.key}
                            scope="col"
                            className="px-4 py-2 border-b border-gray-300 text-left"
                        >
                            {header.label}
                        </th>
                    ))}
                </tr>
            </thead>
            <tbody className="bg-white divide-y divide-gray-200">
                {data.map((row, index) => (
                    <tr key={index}>
                        {headers.map((header) => (
                            <td
                                key={`${index}-${header.key}`}
                                className="px-6 py-4 whitespace-nowrap "
                            >
                                {row[header.key]}
                            </td>
                        ))}
                    </tr>
                ))}
                {data.length === 0 && (
                    <tr>
                        <td
                            colSpan={headers.length}
                            className="px-6 py-4 whitespace-nowrap text-center"
                        >
                            No data available
                        </td>
                    </tr>
                )}
            </tbody>
        </table>
    );
}
