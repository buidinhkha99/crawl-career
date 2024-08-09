import { Suspense } from "react";
import { Row, Col, Form } from "antd";

import { Loading } from "../Loading";
import { moockApi } from "./Login.constants";
import { Container } from "../../Container";
import { renderInput } from "../../../helpers/renderInput";
import { formLogin } from "../../../common/constants";
import { Button } from "../Button";

export default function Login({ background, indexItem }) {
    const [form] = Form.useForm();
    const handleFinish = (values) => {
    //  to do
    };

    return (
        <Suspense fallback={<Loading />}>
            <div
                className="login backgroundImg"
                id={config?.id}
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
            >
                <Container>
                    <Row
                        gutter={{
                            xxl: 80,
                            xl: 60,
                            lg: 40,
                            md: 30,
                            sm: 20,
                            xs: 20,
                        }}
                        className="login__row"
                    >
                        <Col lg={12} xs={24}>
                            {moockApi.img && (
                                <div className="login__img">
                                    <img src={moockApi.img} alt="img login" />
                                </div>
                            )}
                        </Col>
                        <Col
                            lg={12}
                            xs={24}
                            className="login--info"
                        >
                            <h2 className="login__h2">
                                Login
                            </h2>
                            <Form
                                form={form}
                                onFinish={handleFinish}
                                className="login__form"
                            >
                                {formLogin?.map((item, index) => (
                                    <div key={item.placeholder + index}>
                                        {renderInput(item.type, {
                                            layout: "icon",
                                            form_item_name: item.name,
                                            disabled: false,
                                            rounded_full: item.rounded_full,
                                            rules: item.rules,
                                            inputType: item.type,
                                            placeholder: item.placeholder,
                                            prefix: null,
                                        })}
                                    </div>
                                ))}
                                <Form.Item>
                                    {(moockApi.config.icon?.data ||
                                        moockApi.config.text) && (
                                        <Button
                                            config={moockApi.config}
                                            className="login__button"
                                        />
                                    )}
                                </Form.Item>
                            </Form>
                        </Col>
                    </Row>
                </Container>
            </div>
        </Suspense>
    );
}
