import React from "react";
import { Sidebar, Menu, MenuItem } from "react-pro-sidebar";
import { Link, usePage, useForm } from "@inertiajs/react";
import {
    Users,
    PanelRightClose,
    PanelRightOpen,
    UserCheck,
    UserPlus,
    FileText,
    ClipboardList,
    ListChecks,
    LogOut,
} from "lucide-react";
import { route } from 'ziggy-js';

interface MenuItemType {
    icon: React.ReactNode;
    label: string;
    link: string;
}

export function CoordinatorSidebar() {
    const [collapsed, setCollapsed] = React.useState(false);
    const { url } = usePage();
    const { get } = useForm();

    const handleLogout = (e: React.FormEvent) => {
        e.preventDefault();
        get("/logout");
    };

    const menuItemsPSM1: MenuItemType[] = [
        {
            icon: <Users />,
            label: "List Students",
            link: route('coordinator.PSM1.listStudents'),
        },
        {
            icon: <UserCheck />,
            label: "List Panels",
            link: route('coordinator.PSM1.listPanels'),
        },
        {
            icon: <UserPlus />,
            label: "Assign Supervisor",
            link: route('coordinator.PSM1.assignSupervisor'),
        },
        {
            icon: <UserPlus />,
            label: "Assign Proposal Panel",
            link: route('coordinator.PSM1.assignProposalPanel'),
        },
        {
            icon: <UserPlus />,
            label: "Assign PSM1 Panel",
            link: route('coordinator.PSM1.assignPSM1Panel'),
        },
        {
            icon: <FileText />,
            label: "View Result",
            link: route('coordinator.PSM1.viewResult'),
        },
        {
            icon: <ClipboardList />,
            label: "Evaluation Rubric",
            link: route('coordinator.PSM1.evaluationRubric'),
        },
        {
            icon: <ListChecks />,
            label: "Grade Supervision",
            link: route('coordinator.PSM1.gradeSupervision'),
        },
        {
            icon: <ListChecks />,
            label: "Grade Proposal",
            link: route('coordinator.PSM1.gradeProposal'),
        },
        {
            icon: <ListChecks />,
            label: "Grade PSM1",
            link: route('coordinator.PSM1.gradePSM1'),
        },
    ];

    const menuItemsPSM2: MenuItemType[] = [
        {
            icon: <Users />,
            label: "List Students",
            link:route('coordinator.PSM2.listStudents'),
        },
        {
            icon: <UserCheck />,
            label: "List Panels",
            link: route('coordinator.PSM2.listPanels'),
        },
        {
            icon: <UserPlus />,
            label: "Assign PSM2 Panel",
            link: route('coordinator.PSM2.assignPSM2Panel'),
        },
        {
            icon: <FileText />,
            label: "View Result",
            link: route('coordinator.PSM2.viewResult'),
        },
        {
            icon: <ClipboardList />,
            label: "Evaluation Rubric",
            link: route('coordinator.PSM2.evaluationRubric'),
        },
        {
            icon: <ListChecks />,
            label: "Grade Supervision",
            link: route('coordinator.PSM2.gradeSupervision'),
        },
        {
            icon: <ListChecks />,
            label: "Grade PSM2",
            link: route('coordinator.PSM2.gradePSM2'),
        },
    ];

    let menuItems: MenuItemType[] = [];
    if (url === "/Coordinator/Home") {
        menuItems = [];
    } else if (url.startsWith("/Coordinator/PSM1")) {
        menuItems = menuItemsPSM1;
    } else if (url.startsWith("/Coordinator/PSM2")) {
        menuItems = menuItemsPSM2;
    }

    return (
        <div className="flex h-full !text-[#808080] bg-[#FFFFFF]">
            <Sidebar collapsed={collapsed} backgroundColor="#FFFFFF">
                <Menu>
                    <MenuItem
                        onClick={() => setCollapsed(!collapsed)}
                        icon={collapsed && <PanelRightClose />}
                    >
                        <div className="flex justify-between">
                            <span className="pl-3 ">Coordinator</span>
                            <PanelRightOpen />
                        </div>
                    </MenuItem>

                    {menuItems.map(({ icon, label, link }, index) => {
                        const isActive = url === new URL(link, window.location.origin).pathname;
                        const iconColor = isActive
                            ? "text-[#6D2323] font-semibold"
                            : "text-[#808080]";

                        return (
                            <MenuItem
                                key={index}
                                icon={icon}
                                className={iconColor}
                                component="div"
                            >
                                <Link
                                    href={link}
                                    style={{
                                        textDecoration: "none",
                                        color: "inherit",
                                    }}
                                >
                                    {label}
                                </Link>
                            </MenuItem>
                        );
                    })}

                        <MenuItem icon={<LogOut />}>
                            <button 
                                type="submit" 
                                className="w-full text-left"
                                onClick={handleLogout}>

                                Log Out
                            </button>
                        </MenuItem>
                </Menu>
            </Sidebar>
        </div>
    );
}
