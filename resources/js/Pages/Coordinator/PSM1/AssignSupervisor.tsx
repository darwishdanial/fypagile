import {CoordinatorLayout} from "../../../Layouts/CoordinatorLayout";
import React from "react";

export default function AssignSupervisor() {
    return (
        <CoordinatorLayout>
            <div className="min-h-screen bg-gray-100 py-10 px-5">
                <div className="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
                    <h1 className="font-bold text-3xl text-center text-blue-600 mb-4">
                    AssignProposalPanel
                    </h1>
                </div>
            </div>
        </CoordinatorLayout>
    );
}
