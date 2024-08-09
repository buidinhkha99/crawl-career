import React, { Fragment, lazy, Suspense } from "react";
import { Loading } from "../shared";

const components = {
    CardContentOne: lazy(() =>
        import("../shared/components/CardContentOne/CardContentOne")
    ),
    CardContentTwo: lazy(() =>
        import("../shared/components/CardContentTwo/CardContentTwo")
    ),
    CardContentThree: lazy(() =>
        import("../shared/components/CardContentThree/CardContentThree")
    ),
    CardContentFour: lazy(() =>
        import("../shared/components/CardContentFour/CardContentFour")
    ),
    CardContentSix: lazy(() =>
        import("../shared/components/CardContentSix/CardContentSix")
    ),
    CardSlideOne: lazy(() =>
        import("../shared/components/CardSlideOne/CardSlideOne")
    ),
    CardSlideTwo: lazy(() =>
        import("../shared/components/CardSlideTwo/CardSlideTwo")
    ),
    CardSlideThree: lazy(() =>
        import("../shared/components/CardSlideThree/CardSlideThree")
    ),
    CardSlideFour: lazy(() =>
        import("../shared/components/CardSlideFour/CardSlideFour")
    ),
    CardSlideSix: lazy(() =>
        import("../shared/components/CardSlideSix/CardSlideSix")
    ),
    Info: lazy(() => import("../modules/Home/Info/Info")),
    Form: lazy(() => import("../shared/components/Form/Form")),
    NavGroup: lazy(() => import("../shared/components/NavGroup/NavGroup")),
    Subscription: lazy(() =>
        import("../shared/components/Subscription/Subscription")
    ),
    Faq: lazy(() => import("../shared/components/Faq/Faq")),
    Post: lazy(() => import("../modules/PostList/Post/Post")),
    PostDetail: lazy(() => import("../modules/PostList/PostDetail/PostDetail")),
    Banner: lazy(() => import("../modules/../shared/components/Banner/Banner")),
    CardTextOnly: lazy(() =>
        import("../modules/../shared/components/CardTextOnly/CardTextOnly")
    ),
    CardImageOnly: lazy(() =>
        import("../modules/../shared/components/CardImageOnly/CardImageOnly")
    ),
    CardContentExpand: lazy(() =>
        import(
            "../modules/../shared/components/CardContentExpand/CardContentExpand"
        )
    ),
    Topic: lazy(() => import("../modules/PostList/Topic/Topic")),
    Popular: lazy(() => import("../modules/PostList/Popular/Popular")),
    Login: lazy(() => import("../shared/components/Login/Login")),
    Register: lazy(() => import("../shared/components/Register/Register")),
    Breadcrumb: lazy(() =>
        import("../shared/components/Breadcrumb/Breadcrumb")
    ),
    // Vimico
    LoginVimico: lazy(() => import("../shared/Vimico/LoginVimico/LoginVimico")),
    UserInfo: lazy(() => import("../shared/Vimico/UserInfo/UserInfo")),
    ExamRules: lazy(() => import("../shared/Vimico/ExamRules/ExamRules")),
    ExamList: lazy(() => import("../shared/Vimico/ExamList/ExamList")),
    InfoFooter: lazy(() => import("../shared/Vimico/InfoFooter/InfoFooter")),
    ExamWork: lazy(() => import("../shared/Vimico/ExamWork/ExamWork")),
    ExamResult: lazy(() => import("../shared/Vimico/ExamResult/ExamResult")),
    InfoExam: lazy(() => import("../shared/Vimico/InfoExam/InfoExam")),
};

export function loadComponent(name) {
    return components[name];
}

export function registerComponents(name, importer) {
    components[name] = importer;
}

export function renderComponents(components) {
    return components?.map((itemComponent, index) => {
        const Component = itemComponent.component;
        if (!Component) {
            return;
        }
        return (
            <Fragment key={index}>
                <Suspense fallback={<Loading />}>
                    <Component
                        background={itemComponent.background}
                        indexItem={itemComponent.indexItem}
                    />
                </Suspense>
            </Fragment>
        );
    });
}
