import { Suspense, useEffect, useState } from "react";
import { Form, Input } from "antd";
import clsx from "clsx";
import { usePage, router } from "@inertiajs/react";

import { Container } from "../../Container";
import { Loading, Button } from "../../components";
import { moockApi } from "./LoginVimico.constants";
import { ModalNotification } from "../components";
import { InfoExam } from "../InfoExam";
import { message } from "../../../common/utils";

export default function LoginVimico({ background, indexItem }) {
    const config = usePage().props.components[indexItem];
    const { flash } = usePage().props;
    const [form] = Form.useForm();
    const [isDisable, setIsDisable] = useState(true);
    const [isOpenModal, setIsOpenModal] = useState(false);
    const [infoUser, setInfoUser] = useState({
        name: "",
        password: "",
    });
    const dataModal = {
        title: "Đăng nhập không thành công!",
        agree: "Đồng ý",
    };
    useEffect(() => {
        if (infoUser.name !== "" && infoUser.password !== "") {
            setIsDisable(false);
        } else {
            setIsDisable(true);
        }
    }, [infoUser]);

    useEffect(() => {
        localStorage.setItem("appDesktop", null);
    }, []);

    useEffect(() => {
        if (flash.message) {
            message.error(flash.message, "Lỗi");
        }
    }, [flash.message]);

    const handleFinish = (values) => {
        router.post(
            "/login",
            { ...values, redirect_after_login: config.redirect_after_login },
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (res) => {
                    form.resetFields();
                },
                onError: (err) => {
                    setIsOpenModal(true);
                },
            }
        );
    };

    const handleAgree = () => {
        setIsOpenModal(false);
    };

    const handlClickLogin = (values) => {
        handleFinish(values);
    };

    return (
        <Suspense fallback={<Loading />}>
            <div
                className="py-10 flex flex-col justify-center items-center flex-1 backgroundImg"
                id={config?.id}
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
            >
                <Container>
                    <div className="flex flex-col gap-[60px] justify-center items-center">
                        <h2 className="uppercase text-[#324376] font-extrabold text-5xl">
                            ĐĂNG NHẬP
                        </h2>
                        <Form
                            form={form}
                            onFinish={handleFinish}
                            className="w-[480px] vimico-customForm"
                            name="form login"
                            autoComplete="none"
                        >
                            <Form.Item
                                name="name"
                                className="vimico-customInput"
                            >
                                <Input
                                    autoComplete="none"
                                    value={infoUser.name}
                                    placeholder="Tên đăng nhập"
                                    onChange={(e) =>
                                        setInfoUser({
                                            ...infoUser,
                                            name: e.target.value.trim(),
                                        })
                                    }
                                />
                            </Form.Item>
                            <Form.Item
                                name="password"
                                className="vimico-customPassword"
                            >
                                <Input.Password
                                    autoComplete="new-password"
                                    value={infoUser.password}
                                    placeholder="Mật khẩu"
                                    onChange={(e) =>
                                        setInfoUser({
                                            ...infoUser,
                                            password: e.target.value.trim(),
                                        })
                                    }
                                />
                            </Form.Item>

                            <Form.Item>
                                <Button
                                    config={config.config_button}
                                    handleClickButton={() =>
                                        handlClickLogin(infoUser)
                                    }
                                    className={clsx(
                                        isDisable
                                            ? "bg-[#A4A4A4] pointer-events-none w-full"
                                            : null,
                                        "w-full"
                                    )}
                                />
                            </Form.Item>
                        </Form>
                    </div>

                    <ModalNotification
                        open={isOpenModal}
                        handleAgree={handleAgree}
                        dataModal={dataModal}
                        className="flex flex-wrap justify-end"
                    />
                </Container>
            </div>
        </Suspense>
    );
}
