"use client";

import { motion } from "motion/react";

const gradientMapping = {
    blue: "linear-gradient(hsl(223, 90%, 52%), hsl(208, 90%, 52%))",
    purple: "linear-gradient(hsl(283, 90%, 54%), hsl(268, 90%, 54%))",
    red: "linear-gradient(hsl(3, 90%, 52%), hsl(348, 90%, 52%))",
    indigo: "linear-gradient(hsl(253, 90%, 52%), hsl(238, 90%, 52%))",
    orange: "linear-gradient(hsl(32, 95%, 54%), hsl(18, 95%, 54%))",
    green: "linear-gradient(hsl(123, 70%, 45%), hsl(108, 70%, 45%))",
    amber: "linear-gradient(hsl(48, 96%, 55%), hsl(37, 96%, 55%))",
    rose: "linear-gradient(hsl(343, 85%, 60%), hsl(358, 85%, 60%))",
    teal: "linear-gradient(hsl(173, 75%, 48%), hsl(158, 75%, 48%))",
    emerald: "linear-gradient(hsl(149, 80%, 45%), hsl(164, 80%, 45%))",
    violet: "linear-gradient(hsl(266, 85%, 60%), hsl(251, 85%, 60%))",
    slate: "linear-gradient(hsl(215, 20%, 60%), hsl(215, 15%, 45%))",
};

const getBackgroundStyle = (color) => {
    if (gradientMapping[color]) {
        return { background: gradientMapping[color] };
    }

    if (typeof color === "string" && color.startsWith("linear-gradient")) {
        return { background: color };
    }

    return { background: gradientMapping.blue };
};

const GlassButton = ({ item }) => {
    const handleClick = () => {
        if (!item?.url) {
            return;
        }

        if ((item?.method || "").toLowerCase() === "post") {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = item.url;
            form.style.display = "none";

            const csrfToken =
                item?.csrf ||
                document.querySelector('meta[name="csrf-token"]')?.content;
            if (csrfToken) {
                const csrfInput = document.createElement("input");
                csrfInput.type = "hidden";
                csrfInput.name = "_token";
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }

            document.body.appendChild(form);
            form.submit();
            return;
        }

        window.location.assign(item.url);
    };

    return (
        <button
            type="button"
            aria-label={item?.label}
            onClick={handleClick}
            className={`relative bg-transparent outline-none w-[3.6em] h-[3.6em] [perspective:22em] [transform-style:preserve-3d] [-webkit-tap-highlight-color:transparent] group ${
                item?.customClass || ""
            }`}
        >
            <span
                className="absolute inset-0 rounded-[1em] block transition-[transform] duration-300 ease-[cubic-bezier(0.83,0,0.17,1)] origin-[100%_100%] rotate-[15deg] group-hover:[transform:rotate(23deg)_translate3d(-0.4em,-0.4em,0.35em)]"
                style={{
                    ...getBackgroundStyle(item?.color),
                    boxShadow: "0.35em -0.35em 0.6em hsla(223, 10%, 10%, 0.2)",
                }}
            ></span>

            <span
                className="absolute inset-0 rounded-[1em] bg-[hsla(0,0%,100%,0.15)] transition-[transform] duration-300 ease-[cubic-bezier(0.83,0,0.17,1)] origin-[70%_50%] flex backdrop-blur-[0.65em] [-webkit-backdrop-filter:blur(0.65em)] group-hover:[transform:translateZ(1.5em)]"
                style={{
                    boxShadow: "0 0 0 0.08em hsla(0, 0%, 100%, 0.3) inset",
                }}
            >
                <span
                    className="m-auto w-[1.1em] h-[1.1em] flex items-center justify-center"
                    aria-hidden="true"
                >
                    <svg
                        className="w-full h-full"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth={1.4}
                            d={item?.iconPath ?? ""}
                        ></path>
                    </svg>
                </span>
            </span>

            <span className="absolute top-[115%] left-1/2 -translate-x-1/2 text-center whitespace-nowrap leading-[1.6] text-xs font-semibold text-slate-800">
                {item?.label}
            </span>
        </button>
    );
};

export default function GlassIcons({ items = [], className = "" }) {
    if (!Array.isArray(items) || items.length === 0) {
        return null;
    }

    return (
        <motion.div
            layout
            className={`grid gap-16 grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 mx-auto py-12 overflow-visible justify-items-center ${className}`}
        >
            {items.map((item, index) => (
                <GlassButton key={`${item?.label}-${index}`} item={item} />
            ))}
        </motion.div>
    );
}
