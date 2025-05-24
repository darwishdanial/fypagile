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
    Info,
    House,
    History,
    UsersRound,
} from "lucide-react";
import { route } from "ziggy-js";

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

    const menuItemsHome: MenuItemType[] = [
        {
            icon: <House />,
            label: "Dashobard",
            link: route("coordinator.home"),
        },
        {
            icon: <History />,
            label: "Panel History",
            link: route("coordinator.panel.history"),
        },
        {
            icon: <Info />,
            label: "ML Data",
            link: route("coordinator.panel.ml-data"),
        },
    ];

    const menuItemsPSM1: MenuItemType[] = [
        {
            icon: <Users />,
            label: "List Students",
            link: route("coordinator.PSM1.listStudents"),
        },
        {
            icon: <UserCheck />,
            label: "List Panels/Supervisors",
            link: route("coordinator.PSM1.listPanels"),
        },
        {
            icon: <UserPlus />,
            label: "Assign Supervisor",
            link: route("coordinator.PSM1.listSupervisor"),
        },
        {
            icon: <UserPlus />,
            label: "Assign PSM1 Panel",
            link: route("coordinator.PSM1.listPSM1Panel"),
        },
        {
            icon: <FileText />,
            label: "View Result",
            link: route("coordinator.PSM1.viewResult"),
        },
        // {
        //     icon: <ClipboardList />,
        //     label: "Evaluation Rubric",
        //     link: route('coordinator.PSM1.evaluationRubric'),
        // },
        {
            icon: <ClipboardList />,
            label: "Development Rubric",
            link: route("coordinator.PSM1.developmentRubric"),
        },
        {
            icon: <ClipboardList />,
            label: "Research Rubric",
            link: route("coordinator.PSM1.researchRubric"),
        },
        {
            icon: <ListChecks />,
            label: "Grade Supervision",
            link: route("coordinator.PSM1.gradeSupervision"),
        },
        {
            icon: <ListChecks />,
            label: "Grade PSM1 Panel",
            link: route("coordinator.PSM1.gradePSM1Panel"),
        },
        {
            icon: <ListChecks />,
            label: "Grade PSM1 Coordinator",
            link: route("coordinator.PSM1.gradePSM1Coordinator"),
        },
        {
            icon: <UsersRound />,
            label: "Student Request",
            link: route("coordinator.PSM1.sv.req"),
        },
    ];

    const menuItemsPSM2: MenuItemType[] = [
        {
            icon: <Users />,
            label: "List Students",
            link: route("coordinator.PSM2.listStudents"),
        },
        {
            icon: <UserCheck />,
            label: "List Panels/Supervisors",
            link: route("coordinator.PSM2.listPanels"),
        },
        {
            icon: <UserPlus />,
            label: "Assign Supervisor",
            link: route("coordinator.PSM2.listSupervisor"),
        },
        {
            icon: <UserPlus />,
            label: "Assign PSM2 Panel",
            link: route("coordinator.PSM2.listPSM2Panel"),
        },
        {
            icon: <FileText />,
            label: "View Result",
            link: route("coordinator.PSM2.viewResult"),
        },
        {
            icon: <ClipboardList />,
            label: "Development Rubric",
            link: route("coordinator.PSM2.developmentRubric"),
        },
        {
            icon: <ClipboardList />,
            label: "Research Rubric",
            link: route("coordinator.PSM2.researchRubric"),
        },
        {
            icon: <ListChecks />,
            label: "Grade Supervision",
            link: route("coordinator.PSM2.gradeSupervision"),
        },
        {
            icon: <ListChecks />,
            label: "Grade PSM2",
            link: route("coordinator.PSM2.gradePSM2"),
        },
                {
            icon: <ListChecks />,
            label: "Grade PSM2 Coordinator",
            link: route("coordinator.PSM2.gradePSM2Coordinator"),
        },
        {
            icon: <UsersRound />,
            label: "Student Request",
            link: route("coordinator.PSM2.sv.req"),
        },
    ];

    let menuItems: MenuItemType[] = [];
    if (url.startsWith("/Coordinator/Home")) {
        menuItems = menuItemsHome;
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
                        const isActive =
                            url ===
                            new URL(link, window.location.origin).pathname;
                        const iconColor = isActive
                            ? "text-[#6D2323] font-semibold bg-gray-100"
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
                            onClick={handleLogout}
                        >
                            Log Out
                        </button>
                    </MenuItem>
                </Menu>
            </Sidebar>
        </div>
    );
}
