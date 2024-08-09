import clsx from "clsx";
import { Form, Input } from "antd";

import "./TextBox.module.css";
import Button from "../Button/Button";
import { usePage } from "@inertiajs/react";

export default function TextBox({
    inputType,
    placeholder,
    className,
    prefix,
    label,
    layout,
    form_item_name,
    rules,
    itemKey,
    rounded_full,
    suffix,
    disabled,
    onClickEvent,
}) {
    const layoutKey = layout.split("-");
    const globalSetting = usePage().props.setting;

    const handleClickButton = () => {
        onClickEvent();
    };

    return (
        <div key={itemKey}>
            <Form.Item name={form_item_name} rules={rules}>
                <Input
                    className={clsx(
                        "salt-input",
                        className,
                        "salt-input__renderInput",
                        rounded_full
                            ? "salt-input__renderInput--rounded"
                            : null,
                        disabled ? "salt-input__renderInput--disabled" : null
                    )}
                    disabled={disabled}
                    prefix={
                        layoutKey.includes("icon") && (
                            <div
                                className={clsx(
                                    "salt-input--prefix",
                                    `bg-[${globalSetting.background_input_form}]`
                                )}
                                dangerouslySetInnerHTML={{
                                    __html: prefix,
                                }}
                            ></div>
                        )
                    }
                    suffix={
                        layoutKey.includes("button") && (
                            <Button
                                config={suffix}
                                handleClickButton={() => handleClickButton()}
                            />
                        )
                    }
                    type={inputType || "text"}
                    placeholder={placeholder}
                    autoFocus={true}
                    label={label || null}
                />
            </Form.Item>
        </div>
    );
}
